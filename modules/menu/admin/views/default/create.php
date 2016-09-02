<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\menu\models\Menus */

$this->title = Yii::t('menu', 'Создание меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', 'Меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-create">
    <div class="page-heading">
        <h1><i class="icon-menu"></i> <?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
