<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Glucose;

/**
 * GlucoseSearch represents the model behind the search form of `frontend\models\Glucose`.
 */
class GlucoseSearch extends Glucose
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'readingid', 'mealid','patientid'], 'integer'],
            [['access_token', 'glucosevalue', 'pickdate', 'time', 'readingType', 'mealtype', 'mealtime', 'createdDate', 'updatedDate','patientname','agegender'], 'safe'],
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
        $query = Glucose::find()->select('glucose.access_token')->innerjoin('userprofile','userprofile.access_token=glucose.access_token')->distinct();

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
            'pickdate' => $this->pickdate,
            'readingid' => $this->readingid,
            'mealid' => $this->mealid,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'glucosevalue', $this->glucosevalue])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'readingType', $this->readingType])
            ->andFilterWhere(['like', 'mealtype', $this->mealtype])
            ->andFilterWhere(['like', 'mealtime', $this->mealtime]);

        return $dataProvider;
    }
}
