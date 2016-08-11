<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\languages\models\Languages;
use app\modules\menu\models\Menus;
use app\modules\menu\models\MenuItems;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItems */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="menu-items-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="widget">
            <div class="widget-content padding">
                <?= $form->field($model, 'menu_id')->dropDownList(Menus::getDropdownList(),
                    ['prompt' => 'Выберите меню']) ?>

                <?= $form->field($model, 'parent_id')->dropDownList(MenuItems::getTreeDropdownList(true)) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'type')->dropDownList(MenuItems::getMenuItemTypes()) ?>

                <?= $form->field($model, 'link')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'params')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'is_new_window')->checkbox() ?>

                <?= $form->field($model, 'is_active')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('menu', 'Создать') : Yii::t('menu',
                        'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
        $(function() {
            $('#menuitems-type').on('change', function() {
                var self = $(this);
                var value = self.val();

                $.getJSON('/admin.php/menu/menu-items/get-type-menu-params',
                {
                        type:value
                    },
                    function(data) {
                                $('#menuitems-params').val('');
                                if (data.header) {
                                    blockUI('#wrapper');
                                    nifty_modal_alert('fadein', data.header, data.html);
                                }
                                $('#menuitems-link').val(data.url);
                    });
            });
        });
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
