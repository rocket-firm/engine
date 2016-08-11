<?php
/**
 * @var $form \yii\widgets\ActiveForm
 * @var $model \yii\db\ActiveRecord
 * @var $attribute string
 * @var $previewPath string
 * @var $fullPath string
 * @var $removable bool
 * @var $hint string
 * @var array $cropOptions
 * @var bool $croppable
 * @var string $cropUrl
 * @var string $uploadUrl
 * @var array $uploadPost
 */
$adaptiveStyle = '';
if ($adaptive == true) {
    $adaptiveStyle = 'style="overflow:auto;"';
}
?>
<div class="async-image-container" data-options='<?= json_encode($cropOptions) ?>'
     data-croppable="<?= (int)$croppable ?>">
    <?=
    $form->field($model, $attribute)->fileInput([
        'class' => 'async-upload',
        'data-url' => $uploadUrl,
        'data-attribute' => $attribute,
        'data-post' => json_encode($uploadPost),
    ]) ?>
    <span class="error error-text async-upload-error <?= $model->hasErrors($attribute) ? '' : 'hidden' ?>">
        <?= $model->getFirstError($attribute) ?>
    </span>
    <?php if ($hint) { ?>
        <div class="help-block">
            <p class="text-info">
                <em><?= $hint ?></em>
            </p>
        </div>
    <?php } ?>
    <div class="row">
        <div
            class="col-md-12 croppable-image-target async-upload-target">
            <div class="image-container" <?= $adaptiveStyle ?>>
                <?php if ($previewPath) { ?>
                    <a class="img-link" href="<?= $previewPath ?>">
                        <?php
                        echo \yii\helpers\Html::img($previewPath, ['class' => 'croppable-image']);
                        ?>
                    </a>
                    <?php
                } ?>
            </div>
            <?php if ($croppable) { ?>
                <a class="btn btn-default crop-btn" href="<?= $cropUrl ?>" data-path="<?= $previewPath ?>">
                    <i class="glyphicon glyphicon-resize-small"></i>
                    Обрезать изображение
                </a>
            <?php } ?>
            <?php if ($removable && (!$model->getIsNewRecord() && !empty($previewPath))) { ?>
                <a class="btn btn-danger delete-image" data-id="<?= $modelId ?>" href="<?= $deleteUrl ?>"><i
                        class="glyphicon glyphicon-basket"></i>Удалить изображение</a>
            <?php } ?>
        </div>
    </div>
</div>
