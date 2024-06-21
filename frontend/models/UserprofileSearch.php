<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Userprofile;

/**
 * UserprofileSearch represents the model behind the search form of `frontend\models\Userprofile`.
 */
class UserprofileSearch extends Userprofile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profileId', 'userId'], 'integer'],
            [['firstName', 'lastName', 'gender', 'profilePic', 'DOB', 'age', 'familyhistory', 'glucosescore', 'diabeticcondition', 'createdDate', 'updatedDate', 'access_token','Mobile'], 'safe'],
            [['weight', 'height'], 'number'],
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
        $query = Userprofile::find();

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
            'profileId' => $this->profileId,
            'userId' => $this->userId,
            'DOB' => $this->DOB,
            'weight' => $this->weight,
            'height' => $this->height,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'profilePic', $this->profilePic])
            ->andFilterWhere(['like', 'age', $this->age])
            ->andFilterWhere(['like', 'familyhistory', $this->familyhistory])
            ->andFilterWhere(['like', 'glucosescore', $this->glucosescore])
            ->andFilterWhere(['like', 'diabeticcondition', $this->diabeticcondition])
            ->andFilterWhere(['like', 'access_token', $this->access_token]);

        return $dataProvider;
    }
}
