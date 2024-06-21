<?php

namespace backend\modules\common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\common\models\Labtests;

/**
 * LabtestsSearch represents the model behind the search form of `backend\modules\common\models\Labtests`.
 */
class LabtestsSearch extends Labtests
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['labTestId', 'updatedBy', 'createdBy'], 'integer'],
            [['testName', 'status', 'createdDate', 'description', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Labtests::find();

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
            'labTestId' => $this->labTestId,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'updatedBy' => $this->updatedBy,
            'createdBy' => $this->createdBy,
        ]);

        $query->andFilterWhere(['like', 'testName', $this->testName])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
