<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace rocketfirm\engine\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

    public $packages = [];

    public function init()
    {
        parent::init();

        $content = @file_get_contents(\Yii::getAlias('@app/assets.json'));
        if (!$content) {
            throw new \Exception('Could not read assests from path ' . \Yii::getAlias('@app/assets.json'));
        }
        $assetsData = json_decode($content, true);

        if (!empty($assetsData['scripts'])) {
            foreach ($assetsData['scripts'] as $script) {
                if (in_array($script['name'], $this->packages) && $this->loadInAjax($script)) {
                    $this->js[] = 'scripts/' . $script['name'] . '.js';
                }
            }
        }

        if (!empty($assetsData['styles'])) {
            foreach ($assetsData['styles'] as $style) {
                if (in_array($style['name'], $this->packages) && $this->loadInAjax($style)) {
                    $this->css[] = 'styles/' . $style['name'] . '.css';
                }
            }
        }

    }

    protected function loadInAjax($file)
    {
        if (empty($file['ajax'])) {
            $file['ajax'] = false;
        }
        if (\Yii::$app->getRequest()->getIsAjax() && $file['ajax'] === false) {
            return false;
        }

        return true;
    }
}
