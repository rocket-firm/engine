<?php
namespace rocketfirm\engine\widgets;

use rocketfirm\engine\ActiveRecord;
use rocketfirm\engine\traits\UploadableAsync;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class ImageUploadAsync extends ImageUpload
{
    /**
     * @var UploadableAsync|ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $uploadUrl = null;

    public $uploadPost = [];

    /**
     * @var string
     */
    public $cropUrl;

    /**
     * @var bool
     */
    public $removable = false;
    public $deleteUrl = 'delete-image';

    public $croppable = true;

    public $cropOptions = [
    ];

    public $adaptive = false;

    public function run()
    {
        if (!$this->uploadUrl) {
            $this->uploadUrl = ['upload-async', 'attribute' => $this->attribute];
        }
        $this->uploadUrl = Url::to($this->uploadUrl);

        if (!$this->previewPath) {
            $this->previewPath = $this->model->getAsyncTempFile($this->attribute, false, true);
        }

        if ($this->croppable) {
            if (!isset($this->cropOptions['trueSize']) && $this->previewPath) {
                $this->cropOptions['trueSize'] = FileHelper::getImageSize($this->previewPath, true);
            }
            if (!$this->cropUrl) {
                $this->cropUrl = ['crop-image'];
            }
            $this->cropUrl = Url::to($this->cropUrl);
        }

        if ($this->removable) {
            if (!$this->deleteUrl) {
                $this->deleteUrl = ['delete-image'];
            }
            $this->deleteUrl = Url::to($this->deleteUrl);
        }

        $this->cropOptions = ArrayHelper::merge([
            'keySupport' => false,
            'aspectRatioStrict' => true
        ], $this->cropOptions);
        return $this->render('imageUploadAsync', $this->getRenderData());
    }

    protected function getRenderData()
    {
        return ArrayHelper::merge(parent::getRenderData(), [
            'croppable' => $this->croppable,
            'cropUrl' => $this->cropUrl,
            'cropOptions' => $this->cropOptions,
            'uploadUrl' => $this->uploadUrl,
            'uploadPost' => $this->uploadPost,
            'removable' => $this->removable,
            'adaptive' => $this->adaptive,
            'deleteUrl' => $this->deleteUrl,
            'modelId' => $this->model->id
        ]);
    }
}
