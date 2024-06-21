<?php

namespace backend\modules\clinics\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
/**
 * This is the model class for table "clinics".
 *
 * @property int $clinicId
 * @property string|null $clinicName
 * @property string|null $cityName
 * @property int|null $cityId
 * @property int|null $stateId
 * @property string|null $stateName
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipaddress
 */
class Clinics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clinics';
    }

    /**
     * {@inheritdoc}
     */
	public $cities;
	public $locations;
	public $password;
    public $confirmpassword;
	public $username;
	public $price;
	public $planName;
	public $firstName;
	public $userPlanId;
    public function rules()
    {
        return [
            [['cityId', 'stateId', 'createdBy', 'updatedBy'], 'integer'],
            [['clinicName', 'cityId','stateId'], 'required'],
			['email', 'email', 'message'=>'Email is not valid'],
            [['clinicName','email','password'], 'required','on'=>'create'],
			[['mobilenumber','email'], 'unique','on'=>'create','targetClass' => '\common\models\User'],
            
            [['createdDate', 'updatedDate','cities','locations','mobilenumber','email',
			'description','profileImage','Status','metaTitle','metaDescription','metaKeywords','seo_url','userId','username','planName','firstName','userPlanId'], 'safe'],
            [['clinicName', 'cityName', 'stateName', 'ipaddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clinicId' => 'Clinic ID',
            'clinicName' => 'Clinic Name',
            'cityName' => 'City Name',
            'cityId' => 'City',
            'stateId' => 'Location',
            'stateName' => 'State Name',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipaddress' => 'Ipaddress',
        ];
    }
	
	public function beforeSave($insert) {
		//echo 'hi';exit;
        $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->stateId])->one();
		if(!empty($city))
		{
          $this->cityName = $city->title;
		}
		if(!empty($location))
		{
			$this->stateName = $location->title;
		}
        if ($this->isNewRecord) 
        {	
			$user = new User();			
			$user->username = $this->clinicName;
			$user->email = $this->email;
			$user->mobilenumber = $this->mobilenumber;
			$user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
			$user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
			$user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
			$user->setPassword($this->password);
			$user->status = User::STATUS_ACTIVE;
			$user->roleId = 6;
			$user->roleName = 'Clinic';
			//print_r($user);exit;
			$user->save();
			$this->userId = $user->id;
            $this->createdDate = date('Y-m-d H:i;s');
            $this->updatedDate = date('Y-m-d H:i;s');
            $this->createdBy = Yii::$app->user->id;
            $this->updatedBy = Yii::$app->user->id;
			$this->ipaddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
       
        } 
        else 
        {
            $this->updatedDate = date('Y-m-d H:i;s');            
            $this->updatedBy = Yii::$app->user->id;
			$this->ipaddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
			
			$user = User::find()->where(['id'=>$this->userId])->one();			
			if($this->Status == 'Active'){
				$user->status = 10;
			}
			else{
				$user->status = 9;
			}
			$user->save();
		}       
        return parent::beforeSave($insert);
    }
	public static function getClinics($id)
    {
        $model = Clinics::find()->where(['stateId' => $id,'Status'=>'Active'])->orderBy('clinicId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$key]['id'] = $value['clinicId'];
            $data[$key]['name'] = $value['clinicName'];
        }
        return $data;
    }
	public static function getClinicsByID($id)
    {
        $model = Clinics::find()->where(['stateId' => $id,'Status'=>'Active'])->orderBy('clinicId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['clinicId']] = $value['clinicName'];
        }
        return $data;
    }
}
