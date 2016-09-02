<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\menu\models\Menus */

$this->title = Yii::t('menu', 'Изменение меню ' . $model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', 'Меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menu', 'Изменение');
?>
<div class="menus-update">

    <div class="page-heading">
        <h1><i class="icon-menu"></i> <?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
