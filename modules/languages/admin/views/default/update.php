<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\languages\models\Languages */

$this->title = Yii::t('languages', 'Изменение языка: ', [
        'modelClass' => 'Languages',
    ]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('languages', 'Языки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('languages', 'Изменение');
?>
<div class="languages-update">
    <div class="page-heading">
        <h1><i class="icon-language"></i><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
