<?php

namespace backend\modules\plans\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\plans\models\Dietplans;

/**
 * DietplansSearch represents the model behind the search form of `backend\modules\plans\models\Dietplans`.
 */
class DietplansSearch extends Dietplans
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId', 'userId', 'mealtypeId', 'itemId', 'quantity', 'createdBy', 'updatedBy'], 'integer'],
            [['time', 'mealtype', 'itemName', 'calories', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Dietplans::find();

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
            'planId' => $this->planId,
            'userId' => $this->userId,
            'mealtypeId' => $this->mealtypeId,
            'itemId' => $this->itemId,
            'quantity' => $this->quantity,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'mealtype', $this->mealtype])
            ->andFilterWhere(['like', 'itemName', $this->itemName])
            ->andFilterWhere(['like', 'calories', $this->calories])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
