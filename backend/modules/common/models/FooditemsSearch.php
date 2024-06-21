<?php

namespace backend\modules\common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\common\models\Fooditems;

/**
 * FooditemsSearch represents the model behind the search form of `backend\modules\common\models\Fooditems`.
 */
class FooditemsSearch extends Fooditems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId', 'createdBy', 'updatedBy', 'ipAddress'], 'integer'],
            [['itemName', 'itemDescription', 'Status', 'createdDate', 'updatedDate'], 'safe'],
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
        $query = Fooditems::find();

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
            'itemId' => $this->itemId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'ipAddress' => $this->ipAddress,
        ]);

        $query->andFilterWhere(['like', 'itemName', $this->itemName])
            ->andFilterWhere(['like', 'itemDescription', $this->itemDescription])
            ->andFilterWhere(['like', 'Status', $this->Status]);

        return $dataProvider;
    }
}
