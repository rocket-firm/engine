<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 11/19/14
 * Time: 2:40 PM
 */

namespace rocketfirm\engine\widgets;


use rocketfirm\engine\Weather;
use yii\base\Widget;

class WeatherWidget extends Widget
{

    public $cities = [
        'Актау' => 2255894,
        'Актобе' => 2264163,
        'Атырау' => 2256059,
        'Алматы' => 2255777,
        'Астана' => 2264962,
        'Караганда' => 56121534,
        'Кокшетау' => 2258896,
        'Костанай' => 2265127,
        'Кызылорда' => 2264637,
        'Павлодар' => 2264769,
        'Петропавловск' => 2264778,
        'Талдыкорган' => 2262387,
        'Тараз' => 2264353,
        'Уральск' => 2264983,
        'Усть-Каменогорск' => 2260839,
        'Шымкент' => 2262028
    ];

    public function run()
    {
        $weather = new Weather();
        $weather->enable_cache = true;
        $weather->cache_path = \Yii::getAlias('@runtime') . '/weather';

        $active = array();
        $forecast = [];

        foreach ($this->cities as $cityName => $cityID) {
            $data = $weather->get_weather_data($cityID);

            if ($data) {
                $active[$cityName] = $cityID;
                $forecast[$cityID] = $data;
            }

        }

        return $this->render('weatherWidget', ['cities' => $active, 'forecast' => $forecast]);

    }
}
