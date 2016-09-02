<?php
/**
 * @var rocketfirm\engine\modules\menu\models\Menus $menu
 */

foreach ($menu->items as $item) {
    echo '<a href="' . \yii\helpers\Url::to($item['url']) . '"  class="footer-nav-item">' . $item['label'] . '</a>';
}
