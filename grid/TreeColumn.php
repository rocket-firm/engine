<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/25/14
 * Time: 17:17
 */

namespace rocketfirm\engine\grid;


use yii\grid\DataColumn;

class TreeColumn extends DataColumn
{
    public $attributeLevel;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);

        return '<div class="col-md-' . (13 - $model->level) . ' col-md-offset-' . ($model->level - 1) . '">' . $value . '</div>';
    }

}
