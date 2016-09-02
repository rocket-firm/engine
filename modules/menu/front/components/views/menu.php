<?php
/**
 * @var rocketfirm\engine\modules\menu\models\Menus $menu
 */

echo \yii\widgets\Menu::widget([
    'items' => $menu->getItems(),
    'options' => ['class' => $cssClass],
    'linkTemplate' => '<a href="{url}" class="link">{label}</a>',
    'route' => ltrim(Yii::$app->request->url, '/'),
    'id' => 'menu-glavnoe-menyu'
]);
