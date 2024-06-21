<?php

namespace backend\modules\clinics\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinics\models\Clinics;
use frontend\models\Userplans;
/**
 * ClinicsSearch represents the model behind the search form of `backend\modules\clinics\models\Clinics`.
 */
class ClinicsSearch extends Clinics
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinicId', 'cityId', 'stateId', 'createdBy', 'updatedBy'], 'integer'],
            [['clinicName', 'cityName', 'stateName', 'createdDate', 'updatedDate', 'ipaddress','username','price','planName','firstName'], 'safe'],
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
        $query = Clinics::find();

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
            'clinicId' => $this->clinicId,
            'cityId' => $this->cityId,
            'stateId' => $this->stateId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'clinicName', $this->clinicName])
            ->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'stateName', $this->stateName])
            ->andFilterWhere(['like', 'ipaddress', $this->ipaddress]);

        return $dataProvider;
    }
	
	public function approval($params)
    {
		$query = new \yii\db\Query();
		/*$query->from(['u' => 'userplans'])
              ->select(['i.username','u.price','u.userPlanId','u.createdDate','p.planName','up.firstName'])
			  ->innerJoin(['i' => 'user'],'`i`.`access_token` = `u`.`access_token`')
			  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `u`.`access_token`')
			  ->leftJoin(['p' => 'plans'],'`p`.`planId` = `u`.`planId`')->where(['u.doctorId'=>0,'u.dieticianId'=>0]);*/
		$query->from(['u' => 'userplans'])
              ->select(['i.username','u.price','u.userPlanId','u.createdDate','p.planName','up.firstName'])
			  ->innerJoin(['i' => 'user'],'`i`.`access_token` = `u`.`access_token`')
			  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `u`.`access_token`')
			  ->leftJoin(['p' => 'plans'],'`p`.`planId` = `u`.`planId`')->where(['u.Status'=>'Un-Subcribed'])->orderBy('userPlanId DESC');
		//print_r($query);exit;
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
        return $dataProvider;
    }

}
