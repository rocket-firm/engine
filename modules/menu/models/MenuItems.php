<?php

namespace rocketfirm\engine\modules\menu\models;

use rocketfirm\engine\LanguageActiveRecord;
use rocketfirm\engine\traits\NestedSetTree;
use rocketfirm\engine\modules\languages\models\Languages;
use creocoder\behaviors\NestedSet;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "menu_items".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $lang_id
 * @property string $title
 * @property string $type
 * @property string $link
 * @property string $params
 * @property integer $is_new_window
 * @property integer $is_active
 * @property string $create_date
 * @property string $update_date
 *
 * @property Languages $lang
 * @property Menus $menu
 */
class MenuItems extends LanguageActiveRecord
{
    use NestedSetTree;
    const TYPE_MODULE = 0;
    const TYPE_LINK = 1;

    static $types = [
        self::TYPE_MODULE => 'Внутренняя ссылка',
        self::TYPE_LINK => 'Внешняя ссылка'
    ];


    public static function find()
    {
        $query = new MenuItemsQuery(get_called_class());

        if (Yii::$app->params['yiiEnd'] == 'admin') {
            return $query->setLanguage();
        }

        return $query;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'lang_id', 'title', 'link'], 'required'],
            [['parent_id', 'menu_id', 'lang_id', 'is_new_window', 'is_active'], 'integer'],
            [['title', 'type', 'link', 'params'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menu', 'ID'),
            'menu_id' => Yii::t('menu', 'Меню'),
            'lang_id' => Yii::t('menu', 'Язык'),
            'parent_id' => Yii::t('menu', 'Родитель'),
            'title' => Yii::t('menu', 'Наименование'),
            'type' => Yii::t('menu', 'Тип'),
            'link' => Yii::t('menu', 'Ссылка'),
            'params' => Yii::t('menu', 'Параметры для внутренней ссылки, формат JSON'),
            'is_new_window' => Yii::t('menu', 'Открывать в новом окне'),
            'is_active' => Yii::t('menu', 'Активность'),
            'create_date' => Yii::t('menu', 'Дата создания'),
            'update_date' => Yii::t('menu', 'Дата изменения'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Languages::className(), ['id' => 'lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menus::className(), ['id' => 'menu_id']);
    }

    public function getUrl()
    {
        if ($this->type == self::TYPE_MODULE) {
            try {
                $params = Json::decode($this->checkJsonParams($this->params));
            } catch (InvalidParamException $e) {
                $params = null;
            }

            if ($params !== null) {
                $url = ArrayHelper::merge(['/' . $this->link], $params);
            } else {
                $url = ['/' . $this->link];
            }

            return $url;
        }

        return $this->link;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_date', 'update_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_date']
                ],
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => NestedSetsBehavior::className(),
                'depthAttribute' => 'level',
                'treeAttribute' => 'root'
            ],
        ];
    }

    public function checkJsonParams()
    {
        return StringHelper::mb_str_replace("'", '"', $this->params);
    }

    public static function getMenuItemTypes()
    {
        if (!empty(Yii::$app->params['menu']['types']) && !is_array(Yii::$app->params['menu']['types'])) {
            throw new InvalidConfigException('В конфигурационном файле нет настроек меню. params.menus.types[]');
        }

        $menuParams = Yii::$app->params['menu']['types'];

        $types = [];
        foreach ($menuParams as $type => $typeParams) {
            $types[$type] = $typeParams['name'];
        }

        return $types;
    }

    public static function getMenuTypeParams($type)
    {
        if (!empty(Yii::$app->params['menu']['types'][$type]) && !is_array(Yii::$app->params['menu']['types'][$type])) {
            throw new InvalidConfigException('В конфигурационном файле нет настроек меню. params.menus.types' . $type . '[]');
        }

        return Yii::$app->params['menu']['types'][$type];
    }


}
