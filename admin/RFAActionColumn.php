<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 10/20/14
 * Time: 5:38 PM
 */

namespace rocketfirm\engine\admin;


use yii\grid\ActionColumn;
use yii\helpers\Html;

class RFAActionColumn extends ActionColumn
{
    public $activeField = 'is_active';
    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{view}` will be replaced by the result of
     * the callback `buttons['view']`. If a callback cannot be found, the token will be replaced with an empty string.
     * @see buttons
     */
    public $template = '{update} {delete}';

    /**
     * Initializes the default button rendering callbacks
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => \Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-success'
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => \Yii::t('yii', 'Delete'),
                    'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'class' => 'btn btn-danger'
                ]);
            };
        }

        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-export"></span>', $url, [
                    'title' => \Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-info',
                    'target' => '_blank'
                ]);
            };
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $buttons = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);

                return call_user_func($this->buttons[$name], $url, $model, $key);
            } else {
                return '';
            }
        }, $this->template);

        return Html::tag('div', $buttons, ['class' => 'btn-group']);
    }

}
