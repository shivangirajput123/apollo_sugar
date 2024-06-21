<?php

namespace backend\modules\users\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use backend\modules\clinics\models\Clinics;
/**
 * This is the model class for table "dietician".
 *
 * @property int $dieticianId
 * @property int|null $userId
 * @property int|null $cityId
 * @property string|null $cityName
 * @property int|null $locationId
 * @property string|null $locationName
 * @property string|null $dieticianName
 * @property string|null $email
 * @property string|null $dieticianDesription
 * @property string|null $profileImage
 * @property string|null $experience
 * @property string|null $qualification
 * @property string|null $membership
 * @property string $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string $ipAddress
 * @property string|null $metaTitle
 * @property string|null $metaDescription
 * @property string|null $metaKeywords
 * @property string|null $seo_url
 */
class Dietician extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dietician';
    }
    public $username;
    public $password;
    public $confirmpassword;
    public $cities;
	public $locations;
	public $clinics;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'cityId', 'locationId','experience'], 'integer'],
            [['dieticianDesription', 'Status', 'metaDescription'], 'string'],
            [['username','email','password'], 'required','on'=>'create'],
			['email', 'email', 'message'=>'Email is not valid'],
			[['mobilenumber','email'], 'unique','on'=>'create','targetClass' => '\common\models\User'],
            [['Status'], 'required'],
            [['password','confirmpassword'], 'string', 'min' => 6,'on'=>'create'],
            ['confirmpassword', 'compare', 'compareAttribute'=>'password',  'skipOnEmpty' => false,'message'=>"Passwords don't match",'on'=>'create' ],
  
            [['createdDate', 'updatedDate','mobilenumber','duration','timings','clinics','clinicName','clinicId'], 'safe'],
            [['cityName', 'locationName', 'dieticianName', 'email', 'profileImage', 'qualification', 'membership', 'metaTitle', 'metaKeywords', 'seo_url'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
			['experience', 'integer', 'min' => 0, 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dieticianId' => 'Dietician ID',
            'userId' => 'User ID',
            'cityId' => 'City',
            'cityName' => 'City Name',
            'locationId' => 'Location',
            'locationName' => 'Location Name',
			'clinicId' => 'Clinic',
            'dieticianName' => 'Dietician Name',
            'email' => 'Email',
            'dieticianDesription' => 'Dietician Desription',
            'profileImage' => 'Profile Image',
            'experience' => 'Experience',
            'qualification' => 'Qualification',
            'membership' => 'Membership',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
            'metaTitle' => 'Meta Title',
            'metaDescription' => 'Meta Description',
            'metaKeywords' => 'Meta Keywords',
            'seo_url' => 'Seo Url',
        ];
    }
	
	public function beforeSave($insert) {
		//echo 'hi';exit;
        $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->locationId])->one();
		$clinic = Clinics::find()->where(['clinicId'=>$this->clinicId])->one();
        if(!empty($city))
		{
          $this->cityName = $city->title;
		}
		if(!empty($location))
		{
			$this->locationName = $location->title;
		}
		if(!empty($clinic))
		{
			$this->clinicName = $clinic->clinicName;
		}
        if ($this->isNewRecord) 
        {			 
			$user = new User();
			$user->username = $this->username;
			$user->email = $this->email;
			$user->mobilenumber = $this->mobilenumber;
			$user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
			$user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
			$user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
			$user->setPassword($this->password);
			$user->status = User::STATUS_ACTIVE;
			$user->roleId = 5;
			$user->roleName = 'Dietician';
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
	public static function getDietician()
    {
        $model = User::find()->where(['roleId' => 5,'Status'=>10])->orderBy('id DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['id']] = $value['username'];
        }
        return $data;
    }
	
	public static function getDieticianByClinicsid($id)
    {
		$clinic = Clinics::find()->where(['userId'=>$id])->one();
        $model = Dietician::find()->where(['clinicId' => $clinic->clinicId])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['userId']] = $value['dieticianName'];
        }
        return $data;
    }
}
