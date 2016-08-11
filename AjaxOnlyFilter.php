<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 8/26/14
 * Time: 11:54
 */

namespace rocketfirm\engine;


use yii\base\Behavior;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\base\ActionEvent;

class AjaxOnlyFilter extends Behavior
{
    /**
     * @var array this property defines the allowed request methods for each action.
     * For each action that should only support limited set of request methods
     * you add an entry with the action id as array key and an array of
     * allowed methods (e.g. GET, HEAD, PUT) as the value.
     * If an action is not listed all request methods are considered allowed.
     *
     * You can use '*' to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by '*'.
     *
     * For example,
     *
     * ~~~
     * [
     *   'create' => ['get', 'post'],
     *   'update' => ['get', 'put', 'post'],
     *   'delete' => ['post', 'delete'],
     *   '*' => ['get'],
     * ]
     * ~~~
     */
    public $actions = [];


    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return boolean
     * @throws HttpException when the request is not ajax.
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (!in_array('*', $this->actions) && !in_array($action, $this->actions)) {
            return $event->isValid;
        }

        if (!\Yii::$app->request->getIsAjax()) {
            $event->isValid = false;
            throw new HttpException(400, 'Only AJAX requests allowed.');
        }

        return $event->isValid;
    }

}
