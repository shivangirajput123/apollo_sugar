<?php

namespace backend\modules\franchies\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
/**
 * This is the model class for table "franchies".
 *
 * @property int $id
 * @property int|null $userId
 * @property string|null $name
 * @property string|null $type
 * @property string|null $mobilenumber
 * @property string|null $cityName
 * @property int|null $cityId
 * @property string|null $locationName
 * @property int|null $locationId
 * @property string $createdDate
 * @property string $updatedDate
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $ipAddress
 */
class Franchies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'franchies';
    }

    /**
     * {@inheritdoc}
     */
	public $email;
    public $password;
    public $confirmpassword;
    public $cities;
	public $locations;
    public function rules()
    {
        return [
            [['userId', 'cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['name','email','password','type', 'mobilenumber'], 'required','on'=>'create'],
			[['mobilenumber'], 'unique','on'=>'create','targetClass' => '\common\models\User'],
            
			['confirmpassword', 'compare', 'compareAttribute'=>'password',  'skipOnEmpty' => false,'message'=>"Passwords don't match",'on'=>'create' ],
            [['createdDate', 'updatedDate','email','password','confirmpassword','cities','locations','Status','age','gender','partnerType','partner','centerCode', 'centerName', 'pccCode'], 'safe'],
            [['name', 'type', 'mobilenumber', 'cityName', 'centerName', 'centerCode', 'pccCode', 'locationName'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'name' => 'Name',
            'type' => 'Type',
            'mobilenumber' => 'Mobile',
            'cityName' => 'City Name',
            'cityId' => 'City ',
            'centerCode' => 'Center Code ',
            'centerName' => 'Center Name ',
            'pccCode' => 'Pcc Code ',
            'locationName' => 'Location Name',
            'locationId' => 'Location ',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'ipAddress' => 'Ip Address',
        ];
    }
	
	public function beforeSave($insert) {
		//echo 'hi';exit;
        $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->locationId])->one();
		
		if(!empty($city))
		{
          $this->cityName = $city->title;
		}
		if(!empty($location))
		{
			$this->locationName = $location->title;
		}
		
        if ($this->isNewRecord) 
        {			 
			$user = new User();			
			$user->username = $this->name;
			$user->email = $this->email;
			$user->mobilenumber = $this->mobilenumber;
			$user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
			$user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
			$user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
			$user->setPassword($this->password);
			$user->status = User::STATUS_ACTIVE;
			$user->roleId = 8;
			$user->roleName = 'Franchies';
			
			//print_r($user);exit;
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
