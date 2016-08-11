<?php
namespace app\components;

use app\components\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string $view
 * @property ActiveRecord $model
 *
 * @property Controller $controller
 */
abstract class Action extends \yii\base\Action
{
    const EVENT_BEFORE_SAVE='beforeSave';
    const EVENT_AFTER_SAVE='afterSave';

    protected $templatePath = '/templates';

    public $viewTemplate = false;
    public $viewTitle = null;
    public $formPath = null;
    public $containerClass = null;

    public $renderData=[];

    protected $_view;

    public function getView()
    {
        if ($this->_view === null)
            $this->_view = $this->id;

        return $this->_view;
    }

    public function setView($view)
    {
        $this->_view = $view;
    }

    public function redirect($actionId = null, $params = array())
    {
        $this->controller->redirectBack($actionId, $params);
    }

    public function render($data)
    {
        $data['_renderData']=$this->getRenderData();
        if ($this->_view === null) {
            if ($this->viewTemplate) {
                if ($this->viewTemplate === true)
                    $this->viewTemplate = $this->id;
                $this->_view = $this->templatePath . '/' . $this->viewTemplate;
                if (is_callable($this->viewTitle))
                {
                    $method=$this->viewTitle;
                    $this->viewTitle=$method($data);
                }
                $data = ArrayHelper::merge($data, [
                    'formPath' => $this->formPath ? : '/' . $this->controller->id . '/_form',
                    'title' => $this->viewTitle,
                    'containerClass' => $this->containerClass
                ]);
            } else {
                $this->_view = $this->id;
            }
        }
        if (Yii::$app->request->isAjax)
            $content = $this->controller->renderPartial($this->_view, $data);
        else
            $content = $this->controller->render($this->_view, $data);
        return $content;
    }

    public function getRenderData()
    {
        if (!is_callable($this->renderData))
            return $this->renderData;
        $method=$this->renderData;
        return $method($this);
    }
}
