<?php
/**
 * @var app\modules\menu\models\Menus $menu
 */

foreach ($menu->items as $item) {
    echo '<li><a href="' . \yii\helpers\Url::to($item['url'][0]) . '">' . $item['label'] . '</a></li>';
}
