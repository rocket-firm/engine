<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\banners\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banners-form">

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <div class="widget">
        <div class="widget-content padding">
            <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'type')->dropDownList(\rocketfirm\engine\modules\banners\models\Banners::$types,
                ['prompt' => 'Выберите размер банера']) ?>

            <?= $form->field($model, 'is_active')->checkbox() ?>

            <?= \yii\bootstrap\Collapse::widget([
                'items' => [
                    [
                        'label' => 'Вставить код банера',
                        'content' => (string)$form->field($model, 'content')->textArea(),
                        'contentOptions' => ['class' => 'in']
                    ],
                    [
                        'label' => 'Загрузить изображение или флэш-баннер',
                        'content' => $form->field($model, 'image')->fileInput()
                            . $form->field($model, 'swf')->fileInput()
                            . $form->field($model, 'swf_width')->textInput()
                            . $form->field($model, 'swf_height')->textInput()
                            . $form->field($model, 'url')->textInput(['maxlength' => 255])
                    ]
                ]
            ]); ?>

            <div class="well">
                <h4>Загруженные банеры</h4>
                <?php if (!empty($model->content)) { ?>
                    <?= $model->content ?>
                <?php } elseif (!empty($model->swf)) { ?>
                    <div class="row">
                        <div class="col-md-6">
                    <span class="swf-banner" id="swf-banner-<?= $model->id ?>"
                          data-swf="<?= '/media/banners/' . $model->swf ?>"
                          data-width="<?= $model->swf_width ?>"
                          data-height="<?= $model->swf_height ?>"></span>
                        </div>

                        <div class="col-md-6">
                            <?= \yii\helpers\Html::img('/media/banners/' . $model->image) ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <?= \yii\helpers\Html::img('/media/banners/' . $model->image) ?>
                <?php } ?>
            </div>

            <?= $form->field($model, 'start_date')->widget(\dosamigos\formhelpers\DatePicker::className(), [
                'language' => 'ru_RU',
                'clientOptions' => [
                    'format' => 'y-m-d',
                    'date' => $model->start_date
                ]
            ]) ?>

            <?= $form->field($model, 'end_date')->widget(\dosamigos\formhelpers\DatePicker::className(), [
                'language' => 'ru_RU',
                'clientOptions' => [
                    'format' => 'y-m-d',
                    'date' => $model->end_date
                ]
            ]) ?>


            <?= $form->field($model, 'bg_color')->widget(\dosamigos\formhelpers\ColorPicker::className(), []) ?>

            <?= $form->field($model, 'positions')->checkboxList(\rocketfirm\engine\modules\banners\models\Banners::$pageTypes); ?>
        </div>
    </div>
    <div class="widget">
        <div class="widget-content padding">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('banners', 'Создать') : Yii::t('banners',
                    'Сохранить'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
