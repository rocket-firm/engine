<?php

// $weather = new YahooWeather();
//
// $weather->enable_cache = true;
// $weather->cache_path = CACHE;
//
// $weather = $weather->get_weather_data(614274);

// Код	Описание
// 0	tornado                 торнадо
// 1	tropical storm          тропический шторм
// 2	hurricane               ураган
// 3	severe thunderstorms    сильные грозы
// 4	thunderstorms           грозы
// 5	mixed rain and snow     дождь со снегом
// 6	mixed rain and sleet    дождь с мокрым снегом
// 7	mixed snow and sleet    снег с мокрым снегом
// 8	freezing drizzle        изморозь
// 9	drizzle                 моросящий дождь
// 10	freezing rain           ледяной дождь
// 11	light rain              легкий дождь
// 12	showers                 дожди
// 13	snow flurries           снежные вихри
// 14	light snow showers      легкие дожди со снегом
// 15	blowing snow            метель
// 16	snow                    снег
// 17	hail                    град
// 18	sleet                   мокрый снег
// 19	dust                    пыль
// 20	foggy                   туман
// 21	haze                    дымка
// 22	smoky                   дымчатый
// 23	blustery                ветренно
// 24	windy                   ветренно
// 25	cold                    холодно
// 26	cloudy                  облачно
// 27	mostly cloudy (night)   переменная облачность (ночь)
// 28	mostly cloudy (day)     переменная облачность (день)
// 29	partly cloudy (night)   облачно (ночь)
// 30	partly cloudy (day)     облачно (день)
// 31	clear (night)           ясно (ночь)
// 32	sunny                   солнечно
// 33	fair (night)            ясно (ночь)
// 34	fair (day)              ясно (день)
// 35	mixed rain and hail     дождь с градом
// 36	hot                     жара
// 37	isolated thunderstorms  местами грозы
// 38	scattered thunderstorms местами грозы
// 39	scattered thunderstorms местами грозы
// 40	scattered showers       местами дождь
// 41	heavy snow              сильный снегопад
// 42	scattered snow showers  местами снегопад
// 43	heavy snow              сильный снегопад
// 44	partly cloudy           облачно
// 45	thundershowers          ливни с грозой
// 46	snow showers            ливневый снег
// 47	isolated thundershowers местами ливни с грозой
// 3200	not available

namespace app\components;

use yii\base\Component;
use yii\base\Exception;
use yii\helpers\VarDumper;

class Weather extends Component
{
    public $woeid;

    public $cache_path = '';
    public $cache_time = 3600;
    public $enable_cache = true;

    private $raw_data;
    private $cache_file;
    private $weather_api_url = '';
    private $weather_api_url_template = 'https://query.yahooapis.com/v1/public/yql?q=';

    private $textToText = array(
        'tornado' => 'торнадо',
        'tropical storm' => 'тропический шторм',
        'hurricane' => 'ураган',
        'severe thunderstorms' => 'сильные грозы',
        'thunderstorms' => 'грозы',
        'mixed rain and snow' => 'дождь со снегом',
        'mixed rain and sleet' => 'дождь с мокрым снегом',
        'mixed snow and sleet' => 'снег с мокрым снегом',
        'freezing drizzle' => 'изморозь',
        'drizzle' => 'моросящий дождь',
        'freezing rain' => 'ледяной дождь',
        'light rain' => 'легкий дождь',
        'light rain shower' => 'легкий дождь',
        'showers' => 'дожди',
        'snow flurries' => 'снежные вихри',
        'light snow showers' => 'легкие дожди со снегом',
        'light snow' => 'небольшой снегом',
        'blowing snow' => 'метель',
        'snow' => 'снег',
        'hail' => 'град',
        'sleet' => 'мокрый снег',
        'dust' => 'пыль',
        'foggy' => 'туман',
        'fog' => 'туман',
        'haze' => 'дымка',
        'smoky' => 'дымчатый',
        'smoke' => 'туман',
        'blustery' => 'ветренно',
        'windy' => 'ветренно',
        'cold' => 'холодно',
        'cloudy' => 'облачно',
        'mostly cloudy' => 'переменная облачность',
        'mostly cloudy (night)' => 'переменная облачность (ночь)',
        'mostly cloudy (day)' => 'переменная облачность (день)',
        'partly cloudy (night)' => 'облачно (ночь)',
        'partly cloudy (day)' => 'облачно (день)',
        'clear (night)' => 'ясно (ночь)',
        'sunny' => 'солнечно',
        'fair (night)' => 'ясно (ночь)',
        'fair (day)' => 'ясно (день)',
        'fair' => 'ясно',
        'mixed rain and hail' => 'дождь с градом',
        'hot' => 'жара',
        'isolated thunderstorms' => 'местами грозы',
        'scattered thunderstorms' => 'местами грозы',
        'scattered showers' => 'местами дождь',
        'heavy snow' => 'сильный снегопад',
        'scattered snow showers' => 'местами снегопад',
        'partly cloudy' => 'облачно',
        'thundershowers' => 'ливни с грозой',
        'snow showers' => 'ливневый снег',
        'isolated thundershowers' => 'местами ливни с грозой'
    );

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->enable_cache = true;
        return true;
    }

    public function get_weather_data($woeid = 0)
    {
        $return = '';
        $this->enable_cache = true;
        if ($this->enable_cache && $this->cache_path) {
            $this->cache_file = $this->cache_path . '/cache.weather.' . $woeid;
            if (($return = $this->load_from_cache()) !== false) {
                return $return;
            }
        }
        $query = "select * from weather.forecast where u = 'c' and woeid =".$woeid;

        $this->weather_api_url = $this->weather_api_url_template . urlencode($query) .'&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

        if ($this->make_request()) {
            $json = json_decode($this->raw_data);

            if(isset($json->query->results->channel->item)){
                $item = $json->query->results->channel->item;
            } else{
                return false;
            }
            if (empty($item)) {
                return false;
            }

            //$item->condition->temp = ceil(5/9*($item->condition->temp-32));

            $return['code'] = str_pad(strval($item->condition->code), 2, '0', STR_PAD_LEFT);
            $return['temp'] = strval($item->condition->temp);
            $return['text'] = (!empty($this->textToText[strtolower(strval($item->condition->text))])) ? $this->textToText[strtolower(strval($item->condition->text))] : '';
        }


        if ($this->enable_cache && $this->cache_path) {
            $this->write_to_cache($return);
        }

        return $return;
    }

    private function load_from_cache()
    {
        if (file_exists($this->cache_file)) {
            $file_time = filectime($this->cache_file);

            if ($_SERVER['REQUEST_TIME'] - $file_time <= $this->cache_time) {
                return unserialize(file_get_contents($this->cache_file));
            }
        }

        return false;
    }

    private function write_to_cache($return)
    {
        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0777, true);
        }

        if (!file_put_contents($this->cache_file, serialize($return))) {
            throw new Exception('Could not save data to cache. Please make sure your cache directory exists and is writable.');
        }
    }

    private function make_request()
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->weather_api_url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla 5.0');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $this->raw_data = curl_exec($curl);

        curl_close($curl);

        if (!$this->raw_data) {
            return false;
        } else {
            return true;
        }
    }
}
