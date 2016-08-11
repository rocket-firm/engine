<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 9/4/14
 * Time: 15:19
 */

namespace rocketfirm\engine\grid;


use yii\base\InvalidConfigException;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class AddMenuItemColumn extends DataColumn
{
    /**
     * Menu type from config file
     * @var string
     */
    public $type;

    /**
     * Route params
     *
     * Example:
     * [paramName => tableField]
     *
     * real example
     * ['id' => 'id', 'status' => 'status_id'...etc]
     * @var array
     */
    public $routeParams = [];

    /**
     * Menu model. Full path with namespace
     * @var string
     */
    public $menuModel = '\app\modules\menu\models\Menus';

    /**
     * Menu Items model. Full path with namespace
     * @var string
     */
    public $menuItemsModel = '\app\modules\menu\models\MenuItems';

    /**
     * Language attribute name
     * @var string
     */
    public $langAttrName = 'lang_id';

    /**
     * Title attribute name
     * @var string
     */
    public $titleAttrName = 'title';


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->header = 'Добавить в меню';

        if (empty($this->type) || empty(\Yii::$app->params['menu']['types'][$this->type])) {
            throw new InvalidConfigException('Тип меню ' . $this->type . ' не найден в конфигурационном файле');
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {

        $menus = new $this->menuModel;
        $menu = $menus->find()->all();

        $items = ArrayHelper::map($menu, 'id', 'title');

        $params = [];
        foreach ($this->routeParams as $name => $attribute) {
            $params[$name] = $model->$attribute;
        }


        $html = '<form action="' . Url::toRoute(['add-menu-item']) . '" method="post">
                ' . Html::dropDownList('menu_id', [], $items) . '
                <input type="hidden" name="type" value="' . $this->type . '">
                <input type="hidden" name="title" value="' . Html::encode($model->{$this->titleAttrName}) . '">';

        if ($this->langAttrName !== false) {
            $html .= '<input type="hidden" name="lang_id" value="' . $model->{$this->langAttrName} . '">';
        }
        $html .= '<input type="hidden" name="route_params" value="' . Html::encode(Json::encode($params)) . '">
                <input type="hidden" name="url" value="' . \Yii::$app->request->url . '">
                <button class="btn" title="Добавить пункт в меню"><i class="glyphicon glyphicon-list"></i></button>
            </form>';

        return $html;
    }


}
