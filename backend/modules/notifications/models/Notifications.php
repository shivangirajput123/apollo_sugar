<?php

namespace backend\modules\notifications\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use backend\modules\notifications\models\Notificationtypes;
/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
	//public $webinarId;
	public $cities;
	public $locations;
	public $webinars;
	public $notificationtypes;
	public $programs;
    public function rules()
    {
        return [
            [['Status', 'title','gender'], 'required'],
            [['Status'], 'string'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['createdDate', 'updatedDate','typeId',
			'type','cityId','cityName','locationId','locationName',
			'webinarId','cities','locations','webinars','notificationtypes',
			'specificprogram','gender','age','agevalue','iswebinar','isNotified',
			'programs','webinarName'], 'safe'],
            [['title', 'description'], 'string', 'max' => 200],
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
            'title' => 'Title',
			'typeId'=>'Type',
            'description' => 'Description',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
			'iswebinar'=>'Is webinar Based',
			'webinarId'=>'Webinars',
        ];
    }
	
	public function beforeSave($insert) {
    
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
       // print_r($this->cityName);exit;
        return parent::beforeSave($insert);
    }
	public static function addUserFcm($data, $fcm_id) {
        
        if(!empty($fcm_id))
		{
			if(count($fcm_id) == 1)
			{
				$token[0] = $fcm_id[0];
			}
			else
			{
				$token = $fcm_id;
			}
			$title = $data['title'];
            $body =  $data['body'];
            $notification = array('title' =>$title , 'message' => $body, 'body' => $body, 'sound' => 'default', 'badge' => '1');
			$fixture_data = array('notification_title' => $title, 'data' => $data);
			$message['data'][0]['title'] = $data['title'];
			$message['data'][1]['body'] = $data['body'];
			$arrayToSend = array('registration_ids' => $token, 'notification' => $notification, 'priority'=>'high', 'message' => $message);
			$newdata = json_encode($arrayToSend);            
			
			//FCM API end-p688oint
			$url = 'https://fcm.googleapis.com/fcm/send';
			//api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
			$server_key = "AAAATOCBBgk:APA91bEOrh5ZYb7okQeTFzQpl-2Tl07EcXqVBp6MNJBVIUGVYNbFZYRkKLXiODVwrgV3KtKF4URWEyhOg2hBLpXmLp7wwd9hujRsLQqm4Yf5AZrDGBP5BUIHluDS3Dk5CicQpnuXowtO";
			//header with content_type api key
			$headers = array(
				'Content-Type:application/json',
				'Authorization:key='.$server_key
			);
			//CURL request to route notification to FCM connection server (provided by Google)
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $newdata);
			$result = curl_exec($ch);
			//print_r($newdata);
			//print_r($result);exit;
			if ($result === FALSE) {
				die('Oops! FCM Send Error: ' . curl_error($ch));
			}
			curl_close($ch);
			return $result;
		}	
		die;        
	}
}
