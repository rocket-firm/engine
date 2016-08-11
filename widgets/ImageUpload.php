<?php
namespace rocketfirm\engine\widgets;
use yii\widgets\ActiveForm;
use yii\widgets\InputWidget;

class ImageUpload extends InputWidget
{
    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var string
     */
    public $hint;

    /**
     * @var string
     */
    public $fullPath;

    /**
     * @var string
     */
    public $previewPath;

    /**
     * @var bool
     */
    public $removable;

	public function run()
	{
        return $this->render('imageUpload', $this->getRenderData());
	}

    protected function getRenderData()
    {
        return [
            'form'=>$this->form,
            'hint'=>$this->hint,
            'fullPath'=>$this->fullPath,
            'previewPath'=>$this->previewPath,
            'removable'=>$this->removable,
            'model'=>$this->model,
            'attribute'=>$this->attribute
        ];
    }
}
