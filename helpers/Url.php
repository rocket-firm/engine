<?php
namespace yii\helpers;
class Url extends BaseUrl
{

    public static function isRoute($c, $a = null)
    {
        if (is_array($c)) {
            foreach ($c as $parts) {
                if (is_array($parts))
                {
                    $isRoute=self::isRoute($parts[0], $parts[1]);
                } else {
                    $isRoute=self::isRoute($parts);
                }
                if ($isRoute)
                    return true;
            }
            return false;
        }
        return \Yii::$app->controller->id == $c && ($a == null || \Yii::$app->controller->action->id == $a);
    }

    public static function host($scheme = true)
    {
        return ($scheme ? 'http://' : '') . \Yii::$app->params['host'];
    }
}