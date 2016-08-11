<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 9/4/14
 * Time: 15:44
 */

namespace rocketfirm\engine\grid;


use rocketfirm\engine\Action;
use rocketfirm\engine\modules\menu\models\MenuItems;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class AddMenuItemAction extends Action
{
    public function run()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException;
        }

        $menuId = (int)\Yii::$app->request->post('menu_id');
        $langId = (int)\Yii::$app->request->post('lang_id', 1);
        $title = Html::decode(\Yii::$app->request->post('title'));
        $type = \Yii::$app->request->post('type');
        $route = \Yii::$app->params['menu']['types'][$type]['route'];
        $params = Html::decode(\Yii::$app->request->post('route_params'));

        $url = \Yii::$app->request->post('url');

        $menuItems = new MenuItems;
        $menuItems->menu_id = $menuId;
        $menuItems->link = $route;
        $menuItems->params = $params;
        $menuItems->type = $type;
        $menuItems->is_active = 1;
        $menuItems->title = $title;
        $menuItems->lang_id = $langId;

        if ($menuItems->saveNode()) {
            \Yii::$app->getSession()->setFlash('addedMenuItem', 'Пункт меню добавлен');
        } else {
            \Yii::$app->getSession()->setFlash('addedMenuItem', 'Пункт меню добавлен');
        }

        return \Yii::$app->controller->redirect($url);
    }
}
