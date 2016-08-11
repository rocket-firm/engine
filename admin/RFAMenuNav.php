<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 10/20/14
 * Time: 5:00 PM
 */

namespace app\components\admin;

use yii\base\InvalidConfigException;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class RFAMenuNav extends Nav
{
    public $activateParents = true;
    public $controller = null;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        Html::removeCssClass($this->options, 'nav');

    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string the rendering result.
     * @throws InvalidConfigException
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;

        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $label = '<i class="' . $item['icon'] . '"></i>' . Html::tag('span', $label);

        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($items !== null) {
            Html::addCssClass($options, 'has-sub');

            $label .= ' ' . Html::tag('span', Html::tag('i', '', ['class' => 'fa fa-angle-down']),
                    ['class' => 'pull-right']);

            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }
                if ($active) {
                    Html::addCssStyle($subOptions, ['display' => 'block']);
                } else {
                    Html::removeCssStyle($subOptions, 'display');
                }

                $items = RFAMenuNav::widget([
                    'items' => $items,
                    'options' => $subOptions
                ]);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
            Html::addCssClass($linkOptions, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    protected function isItemActive($item)
    {
        $active = parent::isItemActive($item);

        if ($active === false) {
            if (isset($item['controller']) && (\Yii::$app->controller->id === $item['controller'] || \Yii::$app->controller->module->id === $item['controller'])) {
                return true;
            }
        }

        return $active;
    }
}
