<?php

namespace backend\modules\franchies\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\franchies\models\Franchies;

/**
 * FranchiesSearch represents the model behind the search form of `backend\modules\franchies\models\Franchies`.
 */
class FranchiesSearch extends Franchies
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['name', 'type', 'mobilenumber', 'cityName', 'centerName', 'centerCode', 'pccCode', 'locationName', 'createdDate', 'updatedDate', 'ipAddress','Status'], 'safe'],
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
        $query = Franchies::find();

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
            'userId' => $this->userId,
            'cityId' => $this->cityId,
            'locationId' => $this->locationId,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'mobilenumber', $this->mobilenumber])
            ->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'locationName', $this->locationName])
			->andFilterWhere(['like', 'locationName', $this->locationName])
            ->andFilterWhere(['like', 'Status', $this->Status]);

        return $dataProvider;
    }
}
