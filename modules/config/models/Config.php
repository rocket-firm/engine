<?php

namespace rocketfirm\engine\modules\config\models;

use rocketfirm\engine\ActiveRecord;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "config".
 *
 * @property integer $id
 * @property string $param
 * @property string $value
 * @property string $title
 */
class Config extends ActiveRecord
{
    /**
     * @var array
     */
    protected static $_params = [];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param', 'title'], 'required'],
            [['param'], 'unique'],
            [
                ['param'],
                function ($attribute, $params) {
                    if (!preg_match('/^[\w]+$/i', $this->$attribute)) {
                        $this->addError($attribute,
                            'Разрешены только латинские символы, цифры и знак _');
                    }
                }
            ],
            [['param', 'value', 'title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'param' => 'Параметр (только латинские символы, без пробелов)',
            'value' => 'Значение',
            'title' => 'Наименование',
        ];

    }

    /**
     * Получить параметр настроек системы
     * @param string $param Имя параметра
     * @param string $default Значение по умолчанию
     * @param bool $create Создать параметр, если отсутствует. По умолчанию TRUE
     * @return string|\yii\db\ActiveRecord
     * @throws Exception
     */
    public static function getParamValue($param, $default = '', $create = true)
    {

        if (empty(self::$_params)) {
            $params = self::find()->asArray()->all();

            foreach ($params as $item) {
                self::$_params[$item['param']] = $item['value'];
            }

        }

        if (isset(self::$_params[$param])) {
            return self::$_params[$param];
        }

        if ($create === true) {
            $model = new Config;
            $model->title = $param;
            $model->param = $param;
            $model->value = $default;
            $model->save();

            return $default;
        }

        throw new Exception('Параметр ' . $param . ' не найден в настройках системы. Добавить его в панели управления');
    }
}
