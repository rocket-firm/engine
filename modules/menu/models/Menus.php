<?php

namespace rocketfirm\engine\modules\menu\models;

use rocketfirm\engine\modules\languages\models\Languages;
use Yii;
use yii\caching\DbDependency;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $title
 * @property integer $is_active
 *
 * @property MenuItems[] $menuItems
 */
class Menus extends \rocketfirm\engine\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_active'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menu', 'ID'),
            'title' => Yii::t('menu', 'Название'),
            'is_active' => Yii::t('menu', 'Активность'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItems::className(), ['menu_id' => 'id']);
    }

    public function getItems()
    {
        $menuItems = $this->getMenuItems()->where(['is_active' => 1])->andWhere(['lang_id' => Languages::getCurrent()->id])->orderBy([
            'root' => SORT_ASC,
            'lft' => SORT_ASC
        ])->all();

        $data = array();
        foreach ($menuItems as $item) {
            $node = ['label' => $item->title, 'url' => $item->getUrl()];

            if ($item->level == 1) {
                $data[$item->id] = $node;
            } elseif ($item->level > 1) {
                if (!isset($data[$item->parent_id])) {
                    $data[$item->parent_id] = [];
                }
                $data[$item->parent_id]['items'][] = $node;
            } else {
                $data[$item->id] = $node;
            }
        }
        return $data;
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->getCache()->set('menu-' . $this->id, $this);

        parent::afterSave($insert, $changedAttributes);
        return true;
    }


}
