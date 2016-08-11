<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\banners\models\Banners */

$this->title = Yii::t('banners', 'Создание баннера');
$this->params['breadcrumbs'][] = ['label' => Yii::t('banners', 'Баннеры'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-create">

    <div class="page-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
