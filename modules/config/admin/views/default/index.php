<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\config\models\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки системы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

    <div class="page-heading">
        <h1><i class="icon-cog"></i><?= Html::encode($this->title) ?></h1>
    </div>

    <p>
        <?= Html::a('Добавить параметр', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="widget">
        <div class="widget-content">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'title',
                        'param',
                        'value',
                        ['class' => 'app\components\admin\RFAActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
