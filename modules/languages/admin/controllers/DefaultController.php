<?php

namespace rocketfirm\engine\modules\languages\admin\controllers;

use Yii;
use rocketfirm\engine\modules\languages\models\Languages;
use rocketfirm\engine\modules\languages\models\LanguageSearch;
use rocketfirm\engine\RFAController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Languages model.
 */
class DefaultController extends RFAController
{
    protected $_modelName = 'rocketfirm\engine\modules\languages\models\Languages';
    public $allowedRoles = ['admin'];

    /**
     * Lists all Languages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Languages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Languages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Languages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Languages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSetLanguage($id)
    {
        $language = $this->findModel($id);

        Yii::$app->getSession()->set('admin-language', $language->id);

        if (!Yii::$app->user->getIsGuest()) {
            $userClass = (Yii::$app->user->getIdentity())::className();

            $userClass::updateAll(['lang_id' => $id], ['id' => Yii::$app->user->id]);
        }

        $this->goBack();
    }

    /**
     * Finds the Languages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Languages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Languages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
