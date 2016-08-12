<?php
namespace yii\helpers;

class StringHelper extends BaseStringHelper
{
    const PATTERN_URL_STRING = '/^([.a-zA-Z0-9_\-])+$/';

    /**
     * @param int $minLength
     * @param int $maxLength
     * @param bool $letters
     * @param bool $numbers
     * @return string
     */
    public static function random($minLength = 10, $maxLength = 20, $letters = true, $numbers = true)
    {
        // символы
        $chars = '';
        if ($letters) {
            $chars .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($numbers) {
            $chars .= '0123456789';
        }

        // длина
        $stringLength = mt_rand($minLength, $maxLength);

        $result = '';
        for ($i = 0; $i < $stringLength; $i++) {
            $result .= $chars[mt_rand(0, mb_strlen($chars) - 1)];
        }

        return $result;
    }

    /**
     * Осуществить прямую (из русского в английский) транслитерацию переданной методу строки.
     */
    public static function transliterate($str)
    {
        static $lookupTable = array(
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'YO',
            'Ж' => 'ZH',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'J',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'CH',
            'Ш' => 'SH',
            'Щ' => 'CSH',
            'Ь' => '',
            'Ы' => 'Y',
            'Ъ' => '',
            'Э' => 'E',
            'Ю' => 'YU',
            'Я' => 'YA',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'csh',
            'ь' => '',
            'ы' => 'y',
            'ъ' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
        );
        return str_replace(array_keys($lookupTable), array_values($lookupTable), $str);
    }

    /**
     * По переданной в метод строке возвращает строку, пригодную для использования в ссылках.
     * Пример: передали "Тестовая строка 123", получили "testovaya-stroka-123".
     */
    public static function url($str)
    {
        // транслитерация строки
        $url = self::transliterate($str);

        // убираем whitespace символы на концах и переводим в нижний регистр
        $url = mb_strtolower(trim($url));

        // убираем дублирующиеся пробелы в центре строки
        for ($i = 0; $i < 10; $i++) {
            $url = str_replace('  ', ' ', $url);
        }

        // пробелы заменяем на дефисы
        $url = self::mb_str_replace(' ', '-', $url);
        $url = self::mb_str_replace("\r", '-', $url);
        $url = self::mb_str_replace("\n", '-', $url);

        // оставляем только латинские цифры, буквы и дефисы
        $url = preg_replace('#[^A-Za-z0-9\-]#ui', '', $url);

        // убираем дублирующиеся дефисы в центре строки
        for ($i = 0; $i < 10; $i++) {
            $url = str_replace('--', '-', $url);
        }

        // если в результате получилась пустая строка или строка длиной в два символа то просто
        // сгенерируем md5 хэш от оригинальной строки с примесью случайности и вернем его
        if (!$url || mb_strlen($url) <= 2) {
            $url = mb_substr(md5($str), 0, 8) . self::random(8, 8, true, true);
        }

        if (mb_strlen($url) > 65) {
            $url = mb_substr($url, 0, 60);
        }

        return $url;
    }

    public static function content($text, $htmlEncode = true, $limit = false)
    {
        $text = str_replace("\r\n", "\n", $text);
        if ($limit && mb_strlen($text, 'UTF-8') >= $limit) {
            $spacePos = mb_strpos($text, " ", $limit - 1, 'UTF-8');
            if ($spacePos === false) {
                $spacePos = mb_strlen($text, 'UTF-8');
            }
            $newLinePos = mb_strpos($text, "\n", $limit - 1, 'UTF-8');
            if ($newLinePos === false) {
                $newLinePos = $spacePos + 1;
            }
            $pos = min($spacePos, $newLinePos);
            $text = mb_substr($text, 0, $pos ? $pos : $limit, 'UTF-8') . "...";
        }
        if ($htmlEncode) {
            $text = Html::encode($text);
        }
        $text = nl2br($text);
        return trim($text);
    }

    /**
     * @deprecate
     * @param $content
     * @param $max
     * @return string
     */
    public static function _truncateHtml($content, $max)
    {
        if (mb_strlen($content, 'UTF-8') <= $max) {
            return $content;
        }
        return mb_substr(strip_tags($content), 0, $max, 'UTF-8') . "...";
    }

    public static function purify($text)
    {
        $purifier = new HtmlPurifier();
        /*$purifier->options = array(
            'HTML.Allowed'=>array('p','a[href]','b','i','u','img','table','tr','td','tbody','th','hr','span','div'),
        );*/
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $config->set('HTML.AllowedComments', "pagebreak");
        $purifier->options = $config;
        $text = $purifier->purify($text);
        return $text;
    }

    public static function plural($n, $form1, $form2, $form5)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) {
            return $form5;
        } else {
            if ($n1 > 1 && $n1 < 5) {
                return $form2;
            } else {
                if ($n1 == 1) {
                    return $form1;
                }
            }
        }

        return $form5;
    }

    /**
     * Мультибайтовый полноценный аналог стандартной функции str_split.
     * @see str_split
     */
    public static function mb_str_split($str)
    {
        return preg_split('~~u', $str, null, PREG_SPLIT_NO_EMPTY);
    }

    public static function utf8_ucfirst($str)
    {
        preg_match_all("~^(.)(.*)$~u", $str, $arr);
        return mb_strtoupper($arr[1][0]) . $arr[2][0];
    }

    /**
     * Мультибайтовый полноценный аналог стандартной функции strtr.
     * @see strtr
     */
    public static function mb_strtr($str, $from, $to)
    {
        return str_replace(self::mb_str_split($from), self::mb_str_split($to), $str);
    }

    public static function mb_str_replace($search, $replace, $subject)
    {
        if (is_array($subject)) {
            foreach ($subject as $key => $val) {
                $subject[$key] = self::mb_str_replace((string)$search, $replace, $subject[$key]);
            }
            return $subject;
        }
        $pattern = '/(?:' . implode('|',
                array_map(create_function('$match', 'return preg_quote($match[0], "/");'), (array)$search)) . ')/u';
        if (is_array($search)) {
            if (is_array($replace)) {
                $len = min(count($search), count($replace));
                $table = array_combine(array_slice($search, 0, $len), array_slice($replace, 0, $len));
                $f = create_function('$match', '$table = ' . var_export($table,
                        true) . '; return array_key_exists($match[0], $table) ? $table[$match[0]] : $match[0];');
                $subject = preg_replace_callback($pattern, $f, $subject);
                return $subject;
            }
        }
        $subject = preg_replace($pattern, (string)$replace, $subject);
        return $subject;
    }

    public static function formatPhone($phone)
    {
        if (strlen($phone) < 9 || $phone['0'] == '+') {
            return $phone;
        }
        $phone = str_replace(' ', '', $phone);
        $phone = '+7 (' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6,
                2) . '-' . substr($phone, 8);
        return $phone;
    }

    public static function formatMoney($amount)
    {
        return number_format($amount, 0, '', ' ');
    }
}
