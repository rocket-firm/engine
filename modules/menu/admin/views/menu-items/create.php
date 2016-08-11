<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItems */

$this->title = Yii::t('menu', 'Создание пункта меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', 'Меню'), 'url' => ['/menu']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', 'Пункты меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-items-create">

    <div class="page-heading">
        <h1><i class="icon-menu"></i><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
