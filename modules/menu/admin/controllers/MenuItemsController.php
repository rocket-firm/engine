<?php

namespace rocketfirm\engine\modules\menu\admin\controllers;

use rocketfirm\engine\ToogleCategoryAction;
use dosamigos\grid\ToggleAction;
use Yii;
use rocketfirm\engine\modules\menu\models\MenuItems;
use rocketfirm\engine\modules\menu\models\MenuItemsSearch;
use rocketfirm\engine\RFAController;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * MenuItemsController implements the CRUD actions for MenuItems model.
 */
class MenuItemsController extends RFAController
{
    protected $_modelName = 'rocketfirm\engine\modules\menu\models\MenuItems';
    public $allowedRoles = ['admin'];

    /**
     * Lists all MenuItems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenuItems model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MenuItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuItems();

        if ($model->load(Yii::$app->request->post())) {
            /**
             * Если родитель не выбран, то делаем категорию корневой
             */
            if ($model->parent_id == 0) {
                if ($model->makeRoot()) {
                    return $this->redirect(['index']);
                }
            } else {
                /**
                 * Если найден, то добавлем его к дереву
                 */
                $rootCategory = MenuItems::findOne($model->parent_id);
                if ($rootCategory === null) {
                    $model->addError('parent_id', 'Родительская категория не найдена');
                } else {
                    if ($model->appendTo($rootCategory)) {
                        return $this->redirect(['index']);
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MenuItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldParentId = $model->parent_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($oldParentId != (int)$model->parent_id && $model->parent_id != 0) {
                $parentCategory = MenuItems::findOne($model->parent_id);
                $model->appendto($parentCategory);
            } else {
                if ($oldParentId != (int)$model->parent_id && $model->parent_id == 0) {
                    $model->makeRoot();
                }
            }

            return $this->redirect(['index']);


        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MenuItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /**
         * @var $model \yii\db\ActiveRecord|\creocoder\nestedsets\NestedSetsBehavior
         */
        $model = $this->findModel($id);

        if ($model->isRoot()) {
            $model->deleteWithChildren();
        } else {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the MenuItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionMove($id, $type)
    {
        /**
         * @var \yii\db\ActiveRecord | \creocoder\nestedsets\NestedSetsBehavior $model
         */
        $model = MenuItems::findOne((int)$id);

        if ($model === null) {
            throw new NotFoundHttpException(404, 'Страница не найдена');
        }

        if (!$model->isRoot()) {
            if ($type == 'up') {
                $prev = $model->prev()->one();
                if ($prev !== null) {
                    $model->insertBefore($prev);
                }
            } else {
                $next = $model->next()->one();
                if ($next !== null) {
                    $model->insertAfter($next);
                }
            }
        } else {
            $curPos = $model->root;
            if ($type == 'up') {
                $prevCategory = MenuItems::find()->where(
                    [
                        'menu_id' => $model->menu_id,
                        'lang_id' => $model->lang_id,
                        'level' => 0
                    ])
                    ->andWhere('root<:root', [':root' => $model->root])
                    ->addOrderBy(['root' => SORT_DESC])->one();

                if ($prevCategory !== null) {
                    $newPos = $prevCategory->root;

                    $allDescendantsCurModel = $model->leaves()->all();
                    $allDescendantsPrevModel = $prevCategory->leaves()->all();

                    foreach ($allDescendantsCurModel as $curItem) {
                        $curItem->root = $newPos;
                        $curItem->save(false);
                    }

                    $model->root = $newPos;
                    $model->save(false);

                    foreach ($allDescendantsPrevModel as $prevItem) {
                        $prevItem->root = $curPos;
                        $prevItem->save();
                    }

                    $prevCategory->root = $curPos;
                    $prevCategory->save();
                }

            } else {
                $nextCategory = MenuItems::find()->where(
                    [
                        'menu_id' => $model->menu_id,
                        'lang_id' => $model->lang_id,
                        'level' => 0
                    ])
                    ->andWhere('root>:root', [':root' => $model->root])
                    ->addOrderBy(['root' => SORT_ASC])->one();

                if ($nextCategory !== null) {
                    $newPos = $nextCategory->root;

                    $allDescendantsCurModel = $model->leaves()->all();
                    $allDescendantsNextModel = $nextCategory->leaves()->all();


                    foreach ($allDescendantsCurModel as $curItem) {
                        $curItem->root = $newPos;
                        $curItem->save(false);
                    }

                    $model->root = $newPos;
                    $model->save();

                    foreach ($allDescendantsNextModel as $prevItem) {
                        $prevItem->root = $curPos;
                        $prevItem->save();
                    }

                    $nextCategory->root = $curPos;
                    $nextCategory->save();
                }

            }
        }
        $this->redirect(array('index'));
    }


    public function actionGetTypeMenuParams($type)
    {
        $params = MenuItems::getMenuTypeParams($type);
        $response = [];

        if (!empty($params['params'])) {
            $html = $this->renderAjax('_menuParams', ['params' => $params]);
            $response['html'] = $html;
            $response['url'] = $params['route'];
            $response['header'] = 'Настройка пункта меню';
        } else {
            if ($params['route'] === false) {
                $response['url'] = '';
            } else {
                $response['url'] = $params['route'];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'toggle' => [
                'class' => ToggleAction::className(),
                'modelClass' => $this->getModelName(),
                'onValue' => 1,
                'offValue' => 0
            ],

        ]);
    }
}
