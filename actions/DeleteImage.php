<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 12/30/14
 * Time: 12:16 PM
 */

namespace app\components\actions;


use app\components\Action;
use yii\web\NotFoundHttpException;

class DeleteImage extends Action
{

    public function run($id, $attribute = 'image')
    {
        /**
         * @var \yii\db\ActiveRecord
         */
        $model = new $this->controller->modelName;
        $model = $model::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException;
        }


        $sizes = $model->getParam('image.sizes');
        foreach ($sizes as $size) {
            @unlink($model->getFilePath($attribute, true, '_' . $size[0] . 'x' . $size[1]));
        }
        @unlink($model->getFilePath($attribute, true));


        $model->$attribute = '';
        $model->save(false, [$attribute]);

    }
}
