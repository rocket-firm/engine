<?php

namespace rocketfirm\engine;

use Imagine\Image\ManipulatorInterface;
use rocketfirm\engine\traits\Uploadable;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\imagine\Image;

class ActiveRecord extends \yii\db\ActiveRecord
{
    use Uploadable;
    
    protected $allValidationRules = [];
    protected $allAttributeLabels = [];

    public static function getDropdownList(
        $title = 'title',
        $key = 'id',
        $condition = [],
        $order = ['title' => SORT_ASC]
    ) {
        $query = static::find();

        if (!empty($condition)) {
            $query->andWhere($condition);
        }
        $result = $query->orderBy($order)->all();

        $list = ArrayHelper::map($result, $key, $title);

        return $list;
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

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if ($this->hasAttribute('create_date') && $this->hasAttribute('update_date')) {
            $behaviors['timestamp'] = [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_date', 'update_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_date']
                ],
                'value' => new Expression('NOW()'),
            ];
        }

        if ($this->hasAttribute('sefname')) {
            $behaviors['sluggable'] = [
                'class' => SluggableBehavior::className(),
                'attribute' => ['id', 'title'],
                'slugAttribute' => 'sefname',
                'immutable' => true,
                'ensureUnique' => true,
            ];
        }

        return $behaviors;
    }

    public function createPreview($attribute)
    {
        $sizes = $this->getParam($attribute);

        ini_set('memory_limit', '256M');

        foreach ($sizes as $size) {
            list($width, $height) = $size;

            $suffix = '_' . $width . 'x' . $height;

            try {
                Image::thumbnail($this->getFilePath($attribute, true),
                    $width, $height, ManipulatorInterface::THUMBNAIL_INSET
                )->save($this->getFilePath($attribute, true, $suffix), ['quality' => 100]);
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    public function getParam($param)
    {
        if (!array_key_exists(static::tableName(), \Yii::$app->params)) {
            return [];
        }

        return ArrayHelper::valueFromPath($param, \Yii::$app->params[static::tableName()]);
    }

    public function getImageUrl($attribute, $width = null, $height = null)
    {
        $suffix = null;
        if ($width !== null && $height !== null) {
            $suffix = '_' . $width . 'x' . $height;
        }

        if (!file_exists($this->getFilePath($attribute, true, $suffix))
            && file_exists($this->getFilePath($attribute, true))
        ) {
            $this->createPreview($attribute);
        }

        if (file_exists($this->getFilePath($attribute, true, $suffix))) {
            return $this->getFilePath($attribute, false, $suffix);
        }

        return Url::to(['/mainpage/no-image', 'wh' => $width . 'x' . $height]);
    }
}
