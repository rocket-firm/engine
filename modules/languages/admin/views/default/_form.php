<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\languages\models\Languages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="languages-form">
    <div class="widget">
        <div class="widget-content padding">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'locale')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'is_active')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('languages', 'Создать') : Yii::t('languages',
                        'Сохранить'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
