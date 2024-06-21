<?php

namespace backend\modules\webinar\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use backend\modules\common\models\Specialties;
use backend\modules\users\models\Doctors;
/**
 * This is the model class for table "webinars".
 *
 * @property int $webnarId
 * @property string|null $webinarName
 * @property string|null $time
 * @property int|null $doctorId
 * @property string|null $doctorName
 * @property int|null $specialityId
 * @property string|null $specialityName
 * @property string $PublishDate
 * @property string $Description
 * @property string $Status
 * @property string $PublishStatus
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class Webinars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'webinars';
    }
	public $cities;
	public $locations;
	public $specialies;
	public $doctors;
	public $isNotified;
    /**
     * {@inheritdoc}
     */
	 public $starttime;
	 public $endtime;
    public function rules()
    {
        return [
            [['doctorId', 'specialityId'], 'integer'],
            [['PublishDate', 'Description', 'Status','webinarName', 'doctorId','starttime','endtime'], 'required'],
            [['PublishDate', 'createdDate', 'updatedDate','cityId','cityName','locationId','locationName','isNotified'], 'safe'],
            [['Description', 'Status', 'PublishStatus'], 'string'],
			[['cities','locations','specialies','doctors','sent','meetingUrl','endtime','starttime'],'safe'],
            [['webinarName', 'time', 'doctorName', 'specialityName', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'webnarId' => 'Webnar ID',
            'webinarName' => 'Webinar Name',
            'starttime' => 'Start Time',
			'endtime' => 'End Time',
            'doctorId' => 'Host',
			'cityId' => 'City',
			'locationId' => 'Location',
            'doctorName' => 'Host',
            'specialityId' => 'Speciality',
            'specialityName' => 'Speciality Name',
            'PublishDate' => 'Publish Date',
            'Description' => 'Description',
            'Status' => 'Status',
            'PublishStatus' => 'Publish Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
			'sent'=>'Send To'
        ];
    }
	public function beforeSave($insert) {
		//echo 'hi';exit;
       
	//	$speciality = Specialties::find()->where(['speciality_id'=>$this->specialityId])->one();
	  //print_r($this->doctorId);exit;
		$doctor = Doctors::find()->where(['userId'=>$this->doctorId])->one();
		
		if(!empty($doctor))
		{
			$this->doctorName = $doctor->doctorName;
		}
        if ($this->isNewRecord) 
        {
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
        }      
        return parent::beforeSave($insert);
    }
	
	public static function getWebinars($id)
    {
        $model = Webinars::find()->where(['cityId' => $id,'Status'=>'Active'])->orderBy('webnarId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$key]['id'] = $value['webnarId'];
            $data[$key]['name'] = $value['webinarName'];
        }
        return $data;
    }
	
	public static function getWebinarsByID()
    {
        $model = Webinars::find()->where(['Status'=>'Active'])->andwhere(['>=','createdDate',date('Y-m-d')])->orderBy('webnarId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['webnarId']] = $value['webinarName'];
        }
        return $data;
    }
}
