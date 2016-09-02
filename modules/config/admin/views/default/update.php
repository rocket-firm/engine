<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\config\models\Config */

$this->title = 'Изменение параметра: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Параметры системы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="config-update">

    <div class="page-heading">
        <h1><i class="icon-cog"></i><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
