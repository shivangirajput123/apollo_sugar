<?php

namespace backend\modules\webinar\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\webinar\models\Webinars;

/**
 * WebinarsSearch represents the model behind the search form of `backend\modules\webinar\models\Webinars`.
 */
class WebinarsSearch extends Webinars
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['webnarId', 'doctorId', 'specialityId', 'createdBy', 'updatedBy'], 'integer'],
            [['webinarName', 'time', 'doctorName', 'specialityName', 'PublishDate', 'Description', 'Status', 'PublishStatus', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Webinars::find()->orderBy('webnarId DESC');

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
            'webnarId' => $this->webnarId,
            'doctorId' => $this->doctorId,
            'specialityId' => $this->specialityId,
            'PublishDate' => $this->PublishDate,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'webinarName', $this->webinarName])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'doctorName', $this->doctorName])
            ->andFilterWhere(['like', 'specialityName', $this->specialityName])
            ->andFilterWhere(['like', 'Description', $this->Description])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'PublishStatus', $this->PublishStatus])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
