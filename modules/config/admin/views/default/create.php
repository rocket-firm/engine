<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\config\models\Config */

$this->title = 'Новый параметр';
$this->params['breadcrumbs'][] = ['label' => 'Параметры системы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

    <div class="page-heading">
        <h1><i class="icon-cog"></i><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
