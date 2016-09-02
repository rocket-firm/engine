<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model rocketfirm\engine\modules\banners\models\Banners */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('banners', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('banners', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('banners', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('banners', 'Вы уверены, что хотите удалить этот баннер?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'priority',
            'title',
            'content',
            'start_date',
            'end_date',
            'is_active',
            'url:url',
            'image',
            'type',
            'swf',
            'swf_width',
            'swf_height',
            'bg_color',
            'create_date',
            'update_date',
        ],
    ]) ?>

</div>
