<?php

namespace rocketfirm\engine\modules\languages\models;

use rocketfirm\engine\ActiveRecord;
use rocketfirm\engine\modules\categories\models\Categories;
use rocketfirm\engine\modules\pages\models\Pages;
use Yii;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property integer $is_active
 *
 * @property Categories[] $categories
 * @property Pages[] $pages
 */
class Languages extends ActiveRecord
{
    public static $current = null;
    public static $adminCurrent = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'code', 'locale'], 'required'],
            [['is_active'], 'integer'],
            [['title', 'code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('languages', 'ID'),
            'title' => Yii::t('languages', 'Наименование'),
            'code' => Yii::t('languages', 'Код'),
            'locale' => 'Локаль',
            'is_active' => Yii::t('languages', 'Активность'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Pages::className(), ['lang_id' => 'id']);
    }

    public static function getLangIdByCode($code)
    {
        $language = self::find()->where(['code' => $code])->one();

        if ($language === null) {
            throw new \InvalidArgumentException('Не найден язык, с кодом ' . $code);
        }

        return $language->id;
    }

//Получение текущего объекта языка
    public static function getCurrent()
    {
        if (self::$current === null) {
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

//Установка текущего объекта языка и локаль пользователя
    public static function setCurrent($code = null)
    {
        $language = self::getLangByCode($code);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->locale;

    }

//Получения объекта языка по умолчанию
    public static function getDefaultLang()
    {
        return Yii::$app->db->cache(function () {
            return Languages::find()->where(['id' => 1])->one();
        }, 3600);
    }

//Получения объекта языка по буквенному идентификатору
    public static function getLangByCode($code = null)
    {
        if ($code === null) {
            return null;
        } else {
            $language = Languages::find()->where(['code' => $code])->one();

            if ($language === null) {
                return null;
            } else {
                return $language;
            }
        }
    }

    public static function getAdminCurrent()
    {
        if (empty(self::$adminCurrent)) {
            $langId = Yii::$app->getSession()->get('admin-language', 1);

            self::$adminCurrent = Languages::findOne($langId);

        }

        return self::$adminCurrent;
    }

}
