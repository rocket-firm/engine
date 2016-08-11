<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 10/24/14
 * Time: 11:33 AM
 */

namespace rocketfirm\engine\widgets;


use rocketfirm\engine\modules\languages\models\Languages;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;

class CheckboxTreeWidget extends Widget
{
    /**
     * Имя модели, для полей
     * @var string
     */
    public $model;

    /**
     * Имя модели, с которой брать список для построения дерева
     * @var string
     */
    public $treeModel;

    /**
     * Имя поля, для формы
     * @var string
     */
    public $attributeName;

    public function run()
    {
        if (empty($this->treeModel)) {
            throw new InvalidParamException('Не установлен параметр $treeModel');
        }

        if (empty($this->model)) {
            throw new InvalidParamException('Не установлен параметр $model');
        }
        /**
         * @var \yii\db\ActiveRecord $model
         */
        $model = $this->treeModel;
        $categories = $model::find()->andWhere([
                'lang_id' => Languages::getAdminCurrent()->id,
                'is_active' => 1
            ])->addOrderBy('root, lft')->all();


        return $this->render('checkboxTree',
            ['categories' => $categories, 'model' => $this->model, 'attributeName' => $this->attributeName]);
    }
}
