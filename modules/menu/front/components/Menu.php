<?php

namespace rocketfirm\engine\modules\menu\front\components;

use rocketfirm\engine\modules\menu\models\Menus;

class Menu extends \yii\base\Widget
{

    public $menuId;
    public $cssClass;
    public $viewFile = 'menu';
    public $id;

    public function run()
    {
        if (empty($this->menuId)) {
            return false;
        }

        $menu = \Yii::$app->db->cache(function () {
            return Menus::find()->joinWith('menuItems')->where(['menus.id' => $this->menuId])->one();
        }, 1000);

        if ($menu === null) {
            return false;
        }

        return $this->render($this->viewFile, ['menu' => $menu, 'cssClass' => $this->cssClass, 'id' => $this->id]);
    }

}
