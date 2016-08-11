<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 10/1/14
 * Time: 11:21
 */

namespace rocketfirm\engine\modules\menu\models;


use rocketfirm\engine\modules\languages\models\Languages;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class MenuItemsQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            [
                'class' => NestedSetsQueryBehavior::className(),
            ],
        ];
    }

    public function setLanguage()
    {
        if (\Yii::$app->params['yiiEnd'] == 'admin') {
            $this->andWhere(['menu_items.lang_id' => Languages::getAdminCurrent()->id]);
        } else {
            $this->andWhere(['menu_items.lang_id' => Languages::getCurrent()->id]);
        }

        return $this;
    }

}
