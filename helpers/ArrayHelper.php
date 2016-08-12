<?php
namespace yii\helpers;
class ArrayHelper extends BaseArrayHelper
{
    public static function valueFromPath($path, $arr)
    {
        $parts = explode('.', $path);
        $path = &$arr;

        foreach ($parts as $e) {
            $path = &$path[$e];
        }
        return $path;
    }

    /**
     * Remove elements from array which have key over than passed key value
     * @param array $array
     * @param integer $key
     * @return array
     */
    public static function removeAfter(array $array, $key)
    {
        $newArray = [];
        foreach ($array as $arrKey => $value) {
            if ($key == $arrKey) {
                break;
            }
            $newArray[$arrKey] = $value;
        }

        return $newArray;
    }

    /**
     * Remove elements from array which have key less than passed key value
     * @param array $array
     * @param integer $key
     * @return array
     */
    public static function removeBefore(array $array, $key)
    {
        $newArray = [];
        foreach ($array as $arrKey => $value) {
            if ($key > $arrKey) {
                continue;
            }
            $newArray[$arrKey] = $value;
        }

        return $newArray;
    }
}
