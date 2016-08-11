<?php
namespace rocketfirm\engine\widgets;

use rocketfirm\engine\ActiveRecord;
use rocketfirm\engine\behaviors\WithMetaTags;
use rocketfirm\engine\traits\UploadableAsync;

class MetaTagFields extends \yii\base\Widget
{
    /**
     * @var \rocketfirm\engine\behaviors\WithMetaTags|ActiveRecord|UploadableAsync
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
