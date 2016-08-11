<?php
namespace app\components\actions;

use app\components\ActiveRecord;
use app\components\traits\UploadableAsync;
use app\components\Action;
use Yii;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

class UploadAsync extends Action
{
    public function run($attribute)
    {
        /** @var UploadableAsync|ActiveRecord $model */
        $model = new $this->controller->modelName;
        $model->setScenario('upload');
        $model->load(Yii::$app->getRequest()->post(), false);
        $name = $model->validateAndSaveAsyncFile($attribute);
        $response = [
            'files' => []
        ];
        $file = $model->$attribute;
        if (!$name) {
            if (!$model->$attribute) {
                throw new HttpException(400);
            }
            $response['success'] = false;
            $response['rules'] = $model->rules();
            $response['files'][] = [
                'name' => $file->name,
                'size' => $file->size,
                'attriubute' => $attribute,
                'error' => $model->getFirstError($attribute)
            ];
        } else {
            $response['success'] = true;
            $path = $model->getTempMediaDirectory() . '/' . $name;
            $size = FileHelper::getImageSize($path, true);
            $response['files'][] = [
                'name' => $file->name,
                'size' => $file->size,
                'width' => $size[0],
                'height' => $size[1],
                'url' => $path
            ];
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}
