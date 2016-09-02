<?php

namespace rocketfirm\engine\modules\menu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use rocketfirm\engine\modules\menu\models\MenuItems;

/**
 * MenuItemsSearch represents the model behind the search form about `rocketfirm\engine\modules\menu\models\MenuItems`.
 */
class MenuItemsSearch extends MenuItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'menu_id', 'lang_id', 'is_new_window', 'is_active'], 'integer'],
            [['title', 'type', 'link', 'create_date', 'update_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MenuItems::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['root' => SORT_ASC, 'lft' => SORT_ASC]],
            'pagination' => false
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'lang_id' => $this->lang_id,
            'is_new_window' => $this->is_new_window,
            'is_active' => $this->is_active,
            'create_date' => $this->create_date,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
