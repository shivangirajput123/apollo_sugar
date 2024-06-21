<?php

namespace backend\modules\callcentre\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\callcentre\models\Callcentre;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use frontend\models\Orders;
use frontend\models\Orderitems;
use Yii;
/**
 * CallcentreSearch represents the model behind the search form of `backend\modules\callcentre\models\Callcentre`.
 */
class CallcentreSearch extends Callcentre
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cityId', 'stateId', 'createdBy', 'updatedBy', 'userId'], 'integer'],
            [['Name', 'cityName', 'stateName', 'mobilenumber', 'email', 'description', 
			'profileImage', 'Status', 'metaTitle', 'metaDescription', 'metaKeywords', 
			'seo_url', 'createdDate', 'updatedDate', 'ipaddress','slotTime','status',
			'slotDate','firstName','doctorName','dieticianName','itemName','bookingStatus','username','price','clinicName','planName'], 'safe'],
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
        $query = Callcentre::find();

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
            'cityId' => $this->cityId,
            'stateId' => $this->stateId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'userId' => $this->userId,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'stateName', $this->stateName])
            ->andFilterWhere(['like', 'mobilenumber', $this->mobilenumber])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'profileImage', $this->profileImage])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'metaTitle', $this->metaTitle])
            ->andFilterWhere(['like', 'metaDescription', $this->metaDescription])
            ->andFilterWhere(['like', 'metaKeywords', $this->metaKeywords])
            ->andFilterWhere(['like', 'seo_url', $this->seo_url])
            ->andFilterWhere(['like', 'ipaddress', $this->ipaddress]);

        return $dataProvider;
    }
	
	public function statuschange($params)
    {
		if(Yii::$app->user->identity->roleName != 'Clinic')
		{
			$query = new \yii\db\Query();
			$query->from(['u' => 'userplans'])
				  ->select(['i.username','u.price','u.access_token','u.userPlanId','u.updatedDate','u.clinicId','u.planId','p.planName','up.firstName','c.clinicName'])
				  ->innerJoin(['i' => 'user'],'`i`.`access_token` = `u`.`access_token`')			  
				  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `u`.`access_token`')
				  ->leftJoin(['c' => 'clinics'],'`c`.`userId` = `u`.`clinicId`')
				  ->leftJoin(['p' => 'plans'],'`p`.`planId` = `u`.`planId`')->where(['u.status'=>'Subcribed'])->orderBy('userPlanId DESC');
			//print_r($query);exit;
        // add conditions that should always apply here
		}
		else
		{
			$query = new \yii\db\Query();
			$query->from(['u' => 'userplans'])
				  ->select(['i.username','u.price','u.access_token','u.userPlanId','u.updatedDate','u.clinicId','u.planId','p.planName','up.firstName','c.clinicName'])
				  ->innerJoin(['i' => 'user'],'`i`.`access_token` = `u`.`access_token`')			  
				  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `u`.`access_token`')
				  ->leftJoin(['c' => 'clinics'],'`c`.`userId` = `u`.`clinicId`')
				  ->leftJoin(['p' => 'plans'],'`p`.`planId` = `u`.`planId`')->where(['u.status'=>'Subcribed','u.clinicId'=>Yii::$app->user->id])->orderBy('userPlanId DESC');
		}
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		$query->andFilterWhere(['like', 'clinicName', $this->clinicName]);
        
        return $dataProvider;
    }
	
	public function upcomingtests($params)
    {
		$query = new \yii\db\Query();
		$query->from(['o' => 'orders'])
              ->select(['o.orderId','o.bookingStatus','o.slotDate','o.slotTime','up.firstName','i.itemName'])
			  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `o`.`access_token`')
			  ->leftJoin(['i' => 'orderitems'],'`o`.`orderId` = `i`.`orderId`')
			   ->where(['o.access_token'=>$params['token']])->orderBy('orderId DESC');
		//print_r($query);exit;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) 
		{
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'slotTime', $this->slotTime])
		->andFilterWhere(['like', 'slotDate', $this->slotDate])
	    ->andFilterWhere(['like', 'i.itemName', $this->itemName])
        ->andFilterWhere(['like', 'o.bookingStatus', $this->bookingStatus]);
        
        return $dataProvider;
    }
	
	public function Doctorconsultations($params)
    {
		
		$query = new \yii\db\Query();
		$query->from(['sb' => 'slotbooking'])
              ->select(['up.firstName','sb.access_token','sb.bookingId','sb.slotTime','sb.status','sb.createdDate','sb.slotDate','d.doctorName'])
			  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `sb`.`access_token`')
			  ->leftJoin(['d' => 'doctors'],'`d`.`doctorId` = `sb`.`doctorId`')->where(['sb.access_token'=>$params]);
			 
		
       // $query = Slotbooking::find()->where(['access_token'=>$params['token']]);

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

        $query->andFilterWhere(['like', 'slotTime', $this->slotTime])
		->andFilterWhere(['like', 'slotDate', $this->slotDate])
	    ->andFilterWhere(['like', 'doctorName', $this->doctorName])
		 ->andFilterWhere(['like', 'sb.status', $this->status])
            ;
        return $dataProvider;
    }
	
	public function Dieticianconsultations($params)
    {
		
        $query = new \yii\db\Query();
		$query->from(['sb' => 'dieticianslotbooking'])
              ->select(['up.firstName','sb.access_token','sb.bookingId','sb.slotTime','sb.status','sb.createdDate','sb.slotDate','d.dieticianName'])
			  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `sb`.`access_token`')
			  ->leftJoin(['d' => 'dietician'],'`d`.`dieticianId` = `sb`.`dieticianId`')->where(['sb.access_token'=>$params]);
			 

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
 $query->andFilterWhere(['like', 'slotTime', $this->slotTime])
		->andFilterWhere(['like', 'slotDate', $this->slotDate])
	    ->andFilterWhere(['like', 'dieticianName', $this->dieticianName])
         ->andFilterWhere(['like', 'sb.status', $this->status])   ;
        
        return $dataProvider;
    }
}
