<?php
/**
 * @var $form \yii\bootstrap\ActiveForm
 * @var $model \app\components\behaviors\WithMetaTags|\app\components\traits\UploadableAsync|\yii\db\ActiveRecord
 * @var $this \yii\web\View
 * @var $id string
 * @var $hasErrors bool
 * @var $imageSize array
 */
?>
<div class="widget">
    <div class="widget-header"><h2 class="widget-toggle">Мета-теги</h2>

        <div class="additional-btn"><a class="widget-toggle" href="#"><i class="icon-down-open-2"></i></a></div>
    </div>
    <div class="widget-content padding" style="display: none;">
        <?= $form->field($model, 'meta_title')->textInput([]) ?>
        <?= $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 3]) ?>
        <?=
        \app\components\widgets\ImageUploadAsync::widget([
            'model' => $model,
            'attribute' => 'metaImageFile',
            'form' => $form,
            'hint' => 'Рекомендуемый размер - 500x500 пикселей',
            'cropOptions' => array(
                'minSize' => $imageSize,
            ),
            'previewPath' => $model->getFilePath('meta_image'),
        ]) ?>
    </div>
</div>
