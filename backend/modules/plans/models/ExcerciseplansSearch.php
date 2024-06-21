<?php

namespace backend\modules\plans\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\plans\models\Excerciseplans;

/**
 * ExcerciseplansSearch represents the model behind the search form of `backend\modules\plans\models\Excerciseplans`.
 */
class ExcerciseplansSearch extends Excerciseplans
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['explanId', 'userId', 'excerciseId', 'updatedBy', 'createdBy'], 'integer'],
            [['time', 'title', 'username','distance', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Excerciseplans::find();

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
            'explanId' => $this->explanId,
            'userId' => $this->userId,
            'excerciseId' => $this->excerciseId,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'updatedBy' => $this->updatedBy,
            'createdBy' => $this->createdBy,
        ]);

        $query->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'distance', $this->distance])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
