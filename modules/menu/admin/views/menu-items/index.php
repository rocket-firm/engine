<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\menu\models\MenuItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('menu', 'Пункты меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', 'Меню'), 'url' => ['/menu']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-items-index">
    <div class="page-heading">
        <h1><i class="icon-menu"></i><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('menu', '<i class="icon-list-add"></i> Создать пункт меню'), ['create'],
            ['class' => 'btn btn-success']) ?>
    </p>

    <div class="widget">
        <div class="widget-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'menu_id',
                        'class' => 'yii\grid\DataColumn',
                        'value' => function ($data) {
                            return $data->menu->title;
                        }
                    ],
                    [
                        'attribute' => 'lang_id',
                        'class' => 'yii\grid\DataColumn',
                        'value' => function ($data) {
                            return $data->lang->title;
                        }
                    ],
                    [
                        'class' => 'app\components\grid\TreeColumn',
                        'attribute' => 'title'
                    ],
                    [
                        'attribute' => 'type',
                        'class' => 'yii\grid\DataColumn',
                        'value' => function ($data) {
                            if (!empty(Yii::$app->params['menu']['types'][$data->type]['name'])) {
                                return Yii::$app->params['menu']['types'][$data->type]['name'];
                            }
                            return 'Type not found';
                        }
                    ],
                    [
                        'value' => function ($data) {
                            return Html::a('<i class="glyphicon glyphicon-arrow-up"></i>',
                                ['move', 'id' => $data->id, 'type' => 'up'], ['class' => 'btn btn-info'])
                            . '&nbsp;&nbsp;' . Html::a('<i class="glyphicon glyphicon-arrow-down"></i>',
                                ['move', 'id' => $data->id, 'type' => 'down'], ['class' => 'btn btn-info']);
                        },
                        'format' => 'raw',
                        'options' => array('align' => 'center')
                    ],
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
