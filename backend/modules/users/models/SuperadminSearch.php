<?php

namespace backend\modules\users\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\users\models\Superadmin;

/**
 * SuperadminSearch represents the model behind the search form of `backend\modules\users\models\Superadmin`.
 */
class SuperadminSearch extends Superadmin
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adminUserId', 'userId','cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['firstName', 'lastName', 'email',  'city', 'location', 'Status', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Superadmin::find();

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
            'adminUserId' => $this->adminUserId,
            'userId' => $this->userId,
            'cityId' => $this->cityId,
            'locationId' => $this->locationId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
