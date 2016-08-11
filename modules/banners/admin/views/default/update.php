<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\banners\models\Banners */

$this->title = Yii::t('banners', 'Изменение банера') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('banners', 'Баннеры'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('banners', 'Изменение');
?>
<div class="banners-update">

    <div class="page-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
