<?php

namespace rocketfirm\engine\modules\banners\models;

use Yii;

/**
 * This is the model class for table "banner_places".
 *
 * @property integer $id
 * @property integer $banner_id
 * @property integer $place
 * @property integer $page
 *
 * @property Banners $banner
 */
class BannerPlaces extends \rocketfirm\engine\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner_places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banner_id', 'place', 'page'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('banners', 'ID'),
            'banner_id' => Yii::t('banners', 'Банер'),
            'place' => Yii::t('banners', 'Место расположение'),
            'page' => Yii::t('banners', 'Страница'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanner()
    {
        return $this->hasOne(Banners::className(), ['id' => 'banner_id']);
    }
}
