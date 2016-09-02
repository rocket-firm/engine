<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel rocketfirm\engine\modules\banners\models\BanersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('banners', 'Баннеры');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-index">

    <div class="page-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('banners', 'Создать баннер'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="widget">
        <div class="widget-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'start_date',
                    'end_date',
                    [
                        'class' => \rocketfirm\engine\admin\RFAToggleColumn::className(),
                        'attribute' => 'is_active',
                    ],
                    ['class' => 'rocketfirm\engine\admin\RFAActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
