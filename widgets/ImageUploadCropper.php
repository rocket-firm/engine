<?php

namespace rocketfirm\engine\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * Class ImageUploadCropper
 * @package app\components\widgets
 * @property  \yii\db\ActiveRecord|\rocketfirm\engine\traits\UploadableAsync $model
 */
class ImageUploadCropper extends InputWidget
{

    /**
     * @var string
     */
    public $uploadUrl = 'upload-async';

    /**
     * @var array
     */
    public $cropOptions = [];
    /**
     * @var bool
     */
    public $croppable = true;
    /**
     * @var string
     */
    public $cropUrl = 'crop-image';
    /**
     * @var bool
     */
    public $removable = true;
    /**
     * @var string
     */
    public $removeUrl = 'delete-image';

    /**
     * @var string
     */
    public $removeAttribute = 'image';

    /**
     * @var string
     */
    public $previewPath;

    public function init()
    {
        parent::init();


        if ($this->uploadUrl === null) {
            throw new InvalidConfigException('You must set "uploadUrl" options property');
        }

        $this->uploadUrl = Url::to([$this->uploadUrl, 'attribute' => $this->attribute]);

        if (!$this->previewPath) {
            $this->previewPath = $this->model->getAsyncTempFile($this->attribute, false, true);
        }

        if ($this->croppable) {
            if ($this->cropUrl === null) {
                throw new InvalidConfigException('Options "croppable" set TRUE, so you must set "cropUrl" options property');
            }
            if (!isset($this->cropOptions['trueSize']) && $this->previewPath) {
                $this->cropOptions['trueSize'] = FileHelper::getImageSize($this->previewPath, true);
            }
        }

        $this->cropUrl = Url::to([$this->cropUrl]);

        $this->cropOptions = ArrayHelper::merge([
            'keySupport' => false,
            'aspectRatioStrict' => true
        ], $this->cropOptions);

        if ($this->removable && $this->removeUrl === null) {
            throw new InvalidConfigException('Options "removable" set TRUE, so you must set "removeUrl" options property');
        }

        $this->removeUrl = Url::to([$this->removeUrl, 'id' => $this->model->id, 'attribute' => $this->removeAttribute]);
    }

    public function run()
    {
        return $this->render('imageUploadCropper', ['widget' => $this, 'model' => $this->model]);
    }

}