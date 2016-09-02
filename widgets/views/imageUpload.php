<?php
/**
 * @var $this \rocketfirm\engine\widgets\ImageUpload
 * @var $form \yii\widgets\ActiveForm
 * @var $model \yii\db\ActiveRecord
 * @var $attribute string
 * @var $previewPath string
 * @var $fullPath string
 * @var $removable bool
 * @var $hint string
 */
?>
<?= $form->field($model, $attribute)->fileInput() ?>
<?php if ($hint) { ?>
    <div class="help-block">
        <p class="text-info">
            <em><?= $hint ?></em>
        </p>
    </div>
<?php } ?>
<?php if ($previewPath) { ?>
    <div class="row">
        <div class="col-xs-6 col-md-3">
                <?php if ($fullPath)
                    echo \yii\helpers\Html::a(
                        \yii\helpers\Html::img(
                            $previewPath, array("target" => "_blank", 'class'=>'thumbnail')
                        ),
                        $fullPath,
                        ['class'=>'thumbnail']
                    );
                else
                    echo \yii\helpers\Html::img($previewPath, ['class'=>'thumbnail']);
                ?>
                <?php if ($removable) { ?>
                <?php } ?>
        </div>
    </div>
<?php } ?>