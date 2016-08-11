<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/16/14
 * Time: 11:43
 */

namespace app\components\rocket;


use yii\base\Module;

class RFModule extends Module
{

    protected $endName;
    private $_viewPath;

    public function init()
    {

        $this->endName = \yii::$app->params['yiiEnd'];

        if ($this->controllerNamespace === null) {
            $class = get_class($this);
            if (($pos = strrpos($class, '\\')) !== false) {
                $this->controllerNamespace = substr($class, 0, $pos) . '\\' . $this->endName . '\\controllers';
            }
        }

    }

    public function getViewPath()
    {
        if ($this->_viewPath !== null) {
            return $this->_viewPath;
        } else {
            return $this->getBasePath() . DIRECTORY_SEPARATOR . $this->endName . DIRECTORY_SEPARATOR . 'views';
        }
    }

}
