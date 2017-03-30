<?php
/**
 * @var \rocketfirm\engine\ActiveRecord|\rocketfirm\engine\traits\UploadableAsync $model
 * @var \rocketfirm\engine\widgets\ImageUploadCropper $widget
 */
use yii\helpers\Html;

?>
<div class="async-image-container" data-options='<?= json_encode($widget->cropOptions) ?>'
     data-croppable="<?= (int)$widget->croppable ?>">

    <?= Html::activeFileInput($model, $widget->attribute, [
        'class' => 'async-upload',
        'data-url' => $widget->uploadUrl,
        'data-attribute' => $widget->attribute,
//        'data-post' => json_encode($uploadPost),
    ]) ?>
    <?= Html::error($model, $widget->attribute); ?>
    <?= Html::activeHint($model, $widget->attribute); ?>
    <div class="row">
        <div
            class="col-md-12 croppable-image-target async-upload-target">
            <div class="image-container">
                <?php if ($widget->previewPath) { ?>
                    <?php
                    echo \yii\helpers\Html::img($widget->previewPath,
                        ['class' => 'croppable-image', 'style' => 'max-width:150px;']);
                    ?>
                <?php } ?>
            </div>
            <?php if ($widget->croppable) { ?>
                <a class="btn btn-default crop-btn" href="<?= $widget->cropUrl ?>"
                   data-path="<?= $widget->previewPath ?>">
                    <i class="glyphicon glyphicon-resize-small"></i>
                    Обрезать изображение
                </a>
            <?php } ?>
            <?php if ($widget->removable && (!$model->getIsNewRecord() && $widget->previewPath)) { ?>
                <a class="btn btn-danger delete-image" data-id="<?= $model->id ?>" href="<?= $widget->removeUrl ?>"><i
                        class="glyphicon glyphicon-basket"></i>Удалить изображение</a>
            <?php } ?>
        </div>
    </div>
</div>