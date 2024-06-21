<?php

namespace backend\modules\users\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
/**
 * This is the model class for table "coach".
 *
 * @property int $coachId
 * @property int|null $userId
 * @property int|null $cityId
 * @property string|null $cityName
 * @property int|null $locationId
 * @property string|null $locationName
 * @property string|null $coachName
 * @property string|null $email
 * @property string|null $coachDesription
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
class Coach extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coach';
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
        return [
            [['userId', 'cityId', 'locationId'], 'integer'],
            [['coachDesription', 'Status', 'metaDescription'], 'string'],
            [['username','email','password'], 'required','on'=>'create'],
            [['Status','cityId','locationId','coachName'], 'required'],
            [['password','confirmpassword'], 'string', 'min' => 6,'on'=>'create'],
            ['confirmpassword', 'compare', 'compareAttribute'=>'password',  'skipOnEmpty' => false,'message'=>"Passwords don't match",'on'=>'create' ],
  
            [['createdDate', 'updatedDate','mobilenumber'], 'safe'],
            [['cityName', 'locationName', 'coachName', 'email', 'profileImage', 'experience', 'qualification', 'membership', 'metaTitle', 'metaKeywords', 'seo_url'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'coachId' => 'Coach ID',
            'userId' => 'User ID',
            'cityId' => 'City',
            'cityName' => 'City Name',
            'locationId' => 'Location',
            'locationName' => 'Location Name',
            'coachName' => 'Coach Name',
            'email' => 'Email',
            'coachDesription' => 'Coach Desription',
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
        $this->cityName = $city->title;
		$this->locationName = $location->title;
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
			$user->roleId = 4;
			$user->roleName = 'Coach';
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
	
	public static function getCoach()
    {
        $model = User::find()->where(['roleId' => 4,'Status'=>10])->orderBy('id DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['id']] = $value['username'];
        }
        return $data;
    }
}
