<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 9/19/14
 * Time: 15:46
 */

namespace app\components\rocket;


use app\modules\languages\models\Languages;
use yii\helpers\Url;
use yii\web\UrlManager;

class RFUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        if (isset($params['lang_id'])) {
            //Если указан идентификатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Languages::findOne($params['lang_id']);

            if ($lang === null) {
                $lang = Languages::getDefaultLang();
            }
            //unset($params['lang_id']);
        } else {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Languages::getCurrent();
        }

        //Получаем сформированный URL(без префикса идентификатора языка)
        $url = parent::createUrl($params);
        //Добавляем к URL префикс - буквенный идентификатор языка
        if ($lang->code != 'ru') {
            if ($url == '/') {
                return '/' . $lang->code;
            } else {
                return '/' . $lang->code . $url;
            }
        }

        return $url;

    }
}
