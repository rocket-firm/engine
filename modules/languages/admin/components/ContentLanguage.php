<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/25/14
 * Time: 16:17
 */

namespace rocketfirm\engine\modules\languages\admin\components;


use rocketfirm\engine\modules\languages\models\Languages;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class ContentLanguage extends Widget
{

    public $url;
    public $currentLanguage;
    public $model;

    public function run()
    {
        if (empty($this->url)) {
            throw new InvalidParamException('Content language: Not set url');
        }

        $langs = Languages::findAll(['is_active' => 1]);

        if (empty($this->currentLanguage) && !empty($langs)) {
            $this->currentLanguage = $langs[0]->id;
        }

        $items = [];
        foreach ($langs as $item) {
            $items[] = ['label' => $item->title, 'url' => ArrayHelper::merge([$this->url], [$this->model . '[lang_id]' => $item->id]), 'active' => ($this->currentLanguage == $item->id) ? true : false];
        }

        return $this->render('contentLanguage', ['items' => $items]);
    }
}
