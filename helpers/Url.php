<?php

namespace yii\helpers;

class Url extends BaseUrl
{

    public static function isRoute($c, $a = null)
    {
        if (is_array($c)) {
            foreach ($c as $parts) {
                if (is_array($parts)) {
                    $isRoute = self::isRoute($parts[0], $parts[1]);
                } else {
                    $isRoute = self::isRoute($parts);
                }
                if ($isRoute) {
                    return true;
                }
            }
            return false;
        }
        return \Yii::$app->controller->id == $c && ($a == null || \Yii::$app->controller->action->id == $a);
    }

    public static function host($scheme = true)
    {
        return ($scheme ? 'http://' : '') . \Yii::$app->params['host'];
    }

    public static function isMainpage()
    {
        $controller = \Yii::$app->controller;
        $default_controller = \Yii::$app->defaultRoute;
        $module = $controller->module->id;

        $isHome = false;

        if (($module . '/' . $controller->id . '/' . $controller->action->id === $default_controller) || ($controller->id . '/' . $controller->action->id === $default_controller)) {
            $isHome = true;
        }

        return $isHome;
    }
}