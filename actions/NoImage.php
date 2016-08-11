<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 10/17/14
 * Time: 4:00 PM
 */

namespace rocketfirm\engine\actions;


use rocketfirm\engine\Action;

class NoImage extends Action
{

    public function run()
    {
        list($width, $height) = explode('x', $_GET['wh']);
        $fill = '999';
        header('Content-Type: image/svg+xml');
        ?>
        <svg viewBox="0 0 <?= $width ?> <?= $height ?>" xmlns="http://www.w3.org/2000/svg">
            <g>
                <rect id="svg_1" height="<?= $height ?>" width="<?= $width ?>" y="0" x="0" fill="#<?= $fill ?>"/>
            </g>
        </svg>
    <?php
    }
}
