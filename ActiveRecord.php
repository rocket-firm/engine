<?php
namespace rocketfirm\engine;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ActiveRecord extends \yii\db\ActiveRecord
{
    protected $allValidationRules = [];
    protected $allAttributeLabels = [];

    public static function getDropdownList($title = 'title', $key = 'id')
    {
        $list = ArrayHelper::map(static::find()->select([$key, $title])->asArray()->all(), $key, $title);
        return $list;
    }

    public function getParam($param)
    {
        return ArrayHelper::valueFromPath($param, \Yii::$app->params[$this->tableName()]);
    }

    public static function getMenuDropdown($title = 'title', $key = 'id')
    {
        $data = static::find()->select([$key, $title])->asArray()->all();

        $items = [];
        foreach ($data as $item) {
            $items[] = [
                'label' => $item[$title],
                'url' => Url::to(['/' . \Yii::$app->requestedRoute, 'lang' => $item[$key]])
            ];
        }

        return $items;
    }

    protected function compareDate($v1, $v2, $key)
    {
        /* Сравниваем значение по ключу date */
        if ($v1["date"] == $v2["date"]) {
            return 0;
        }
        return ($v1["date"] < $v2["date"]) ? 1 : -1;
    }
}
