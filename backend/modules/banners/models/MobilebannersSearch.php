<?php

namespace backend\modules\banners\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\banners\models\Mobilebanners;

/**
 * MobilebannersSearch represents the model behind the search form of `backend\modules\banners\models\Mobilebanners`.
 */
class MobilebannersSearch extends Mobilebanners
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'priority'], 'integer'],
            [['baner_name', 'rate', 'item_name', 'itemcode', 'baner_image', 'created_at', 'updated_at', 'type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Mobilebanners::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'priority' => $this->priority,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'baner_name', $this->baner_name])
            ->andFilterWhere(['like', 'rate', $this->rate])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'itemcode', $this->itemcode])
            ->andFilterWhere(['like', 'baner_image', $this->baner_image])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
