<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 12/8/14
 * Time: 11:49 AM
 */

namespace rocketfirm\engine\widgets;


use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Html;

class RFDateTimePicker extends DateTimePicker
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        unset($this->options['readonly']);
    }
}
