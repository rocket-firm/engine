<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\languages\models\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('languages', 'Языки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="languages-index">
    <div class="page-heading">
        <h1><i class="icon-language"></i><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('languages', '<i class="icon-list-add"></i> Добавить язык', [
            'modelClass' => 'Languages',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
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
                        'code',
                        'locale',
                        [
                            'class' => \app\components\admin\RFAToggleColumn::className(),
                            'attribute' => 'is_active',
                        ],
                        ['class' => \app\components\admin\RFAActionColumn::className()],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
