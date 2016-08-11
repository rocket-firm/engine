<?php
namespace app\components\widgets;

use app\components\ActiveRecord;
use app\components\behaviors\WithMetaTags;
use app\components\traits\UploadableAsync;

class MetaTagFields extends \yii\base\Widget
{
    /**
     * @var \app\components\behaviors\WithMetaTags|ActiveRecord|UploadableAsync
     */
    public $model;

    /**
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    public function run()
    {
        $attrs = ['meta_title', 'meta_description', 'meta_keywords', 'metaImageFile'];
        $hasErrors = false;
        foreach ($attrs as $attr) {
            if ($this->model->hasErrors($attr)) {
                $hasErrors = true;
                break;
            }
        }
        return $this->render('metaTagFields', [
            'model' => $this->model,
            'form' => $this->form,
            'hasErrors' => $hasErrors,
            'id' => $this->id,
            'imageSize' => WithMetaTags::$metaImageSize
        ]);
    }
}
