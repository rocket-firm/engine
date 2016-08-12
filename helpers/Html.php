<?php
namespace yii\helpers;
class Html extends BaseHtml
{
    public static function icon($type)
    {
        return self::tag('span', '', ['class'=>'glyphicon glyphicon-'.$type]);
    }
}