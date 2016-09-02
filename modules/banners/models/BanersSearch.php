<?php

namespace rocketfirm\engine\modules\banners\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use rocketfirm\engine\modules\banners\models\Banners;

/**
 * BanersSearch represents the model behind the search form about `rocketfirm\engine\modules\banners\models\Banners`.
 */
class BanersSearch extends Banners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'is_active', 'type', 'swf_width', 'swf_height'], 'integer'],
            [['title', 'content', 'start_date', 'end_date', 'url', 'image', 'swf', 'bg_color', 'create_date', 'update_date'], 'safe'],
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
        $query = Banners::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'priority' => $this->priority,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'swf_width' => $this->swf_width,
            'swf_height' => $this->swf_height,
            'create_date' => $this->create_date,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'swf', $this->swf])
            ->andFilterWhere(['like', 'bg_color', $this->bg_color]);

        return $dataProvider;
    }
}
