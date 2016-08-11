<?php
namespace rocketfirm\engine\actions;

use rocketfirm\engine\Action;
use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\HttpException;
use yii\web\Response;

class CropImage extends Action
{

    public function run($path)
    {
        $fullPath = \Yii::getAlias('@webroot') . $path;
        if (!file_exists($fullPath)) {
            throw new HttpException(400, 'No such file: ' . $fullPath);
        }
        Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_IMAGICK];
        Image::crop($fullPath, $_POST['w'], $_POST['h'], [$_POST['x'], $_POST['y']])->save($fullPath);
        $size = FileHelper::getImageSize($fullPath);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'success' => true,
            'url' => $path,
            'width' => $size[0],
            'height' => $size[1]
        ];
    }
}
