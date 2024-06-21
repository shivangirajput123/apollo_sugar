<?php

namespace backend\modules\users\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
/**
 * This is the model class for table "superadmin".
 *
 * @property int $adminUserId
 * @property int|null $userId
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string|null $email
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $location
 * @property int|null $countryId
 * @property int|null $stateId
 * @property string $Status
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string $ipAddress
 */
class Superadmin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'superadmin';
    }
    public $username;
    public $password;
    public $confirmpassword;
    public $cities;
	public $locations;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return 
        [
            [['userId', 'cityId', 'locationId'], 'integer'],
            [['username','email','password'], 'required','on'=>'create'],
            [['Status','cityId','locationId','firstName'], 'required'],
            [['password','confirmpassword'], 'string', 'min' => 6,'on'=>'create'],
            ['confirmpassword', 'compare', 'compareAttribute'=>'password',  'skipOnEmpty' => false,'message'=>"Passwords don't match",'on'=>'create' ],
  
            [['Status'], 'string'],
            [['createdDate', 'updatedDate','profileImage','cities','locations'], 'safe'],
            [['firstName', 'lastName', 'email', 'city', 'location', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adminUserId' => 'Admin User ID',
            'userId' => 'User ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
          
            'city' => 'City',
            'location' => 'Location',
            'cityId' => 'City',
            'locationId' => 'Location',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
	public function beforeSave($insert) {
		//echo 'hi';exit;
        $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->locationId])->one();
        $this->city = $city->title;
		$this->location = $location->title;
        if ($this->isNewRecord) 
        {			 
			$user = new User();
			$user->username = $this->username;
			$user->email = $this->email;
			$user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
			$user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
			$user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
			$user->setPassword($this->password);
			$user->status = User::STATUS_ACTIVE;
			$user->roleId = 2;
			$user->roleName = 'Admin User';
			$user->save();
			$this->userId = $user->id;
            $this->createdDate = date('Y-m-d H:i;s');
            $this->updatedDate = date('Y-m-d H:i;s');
            $this->createdBy = Yii::$app->user->id;
            $this->updatedBy = Yii::$app->user->id;
			$this->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
       
        } 
        else 
        {
            $this->updatedDate = date('Y-m-d H:i;s');            
            $this->updatedBy = Yii::$app->user->id;
			$this->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
       
			$user = User::find()->where(['id'=>$this->userId])->one();			
			if($this->Status == 'Active'){
				$user->status = 10;
			}
			else{
				$user->status = 9;
			}
			$user->save();
        }
       // print_r($this->cityName);exit;
        return parent::beforeSave($insert);
    }
}
