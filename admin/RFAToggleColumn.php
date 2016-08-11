<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 10/23/14
 * Time: 11:27 AM
 */

namespace app\components\admin;

use dosamigos\grid\ToggleColumn;

class RFAToggleColumn extends ToggleColumn
{
    public $onValue = 1;
    public $onLabel = 'Дв';
    public $offLabel = 'Нет';
    public $contentOptions = ['class' => 'col-md-1 text-center'];
    public $headerOptions = ['class' => 'col-md-1'];
    public $onIcon = 'glyphicon glyphicon-ok-circle btn btn-success';
    public $offIcon = 'glyphicon glyphicon-remove-circle btn btn-default';
    public $filter = [
        1 => 'Активный',
        0 => 'Не активный'
    ];
}
