<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\languages\models\Languages */

$this->title = Yii::t('languages', 'Создание языка', [
    'modelClass' => 'Языки',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('languages', 'Языки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="languages-create">
    <div class="page-heading">
        <h1><i class="icon-language"></i><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
