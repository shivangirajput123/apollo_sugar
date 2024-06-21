<?php

namespace backend\modules\callcentre\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use backend\modules\packages\models\Plandetails;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use frontend\models\Orders;
use backend\modules\packages\models\ItemDetails;
use frontend\models\Orderitems;
use backend\modules\users\models\Doctors;
use backend\modules\users\models\Dietician;
/**
 * This is the model class for table "callcentre".
 *
 * @property int $id
 * @property string|null $Name
 * @property string|null $cityName
 * @property int|null $cityId
 * @property int|null $stateId
 * @property string|null $stateName
 * @property string|null $mobilenumber
 * @property string|null $email
 * @property string|null $description
 * @property string|null $profileImage
 * @property string|null $Status
 * @property string|null $metaTitle
 * @property string|null $metaDescription
 * @property string|null $metaKeywords
 * @property string|null $seo_url
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipaddress
 * @property int|null $userId
 */
class Callcentre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'callcentre';
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
	public $slotTime;
	public $slotDate;
	public $doctorName;
	public $status;
	public $dieticianName;
	public $bookingStatus;
	public $itemName;
	public $clinicName;
	public $clinicId;
    public function rules()
    {
        return [
            [['cityId', 'stateId', 'createdBy', 'updatedBy', 'userId'], 'integer'],
            [['description', 'metaDescription'], 'string'],
            [['Name', 'cityId','stateId'], 'required'],
			['email', 'email', 'message'=>'Email is not valid'],
            [['Name','email','password'], 'required','on'=>'create'],
			[['mobilenumber','email'], 'unique','on'=>'create','targetClass' => '\common\models\User'],
            
           [['createdDate', 'updatedDate','cities','locations','mobilenumber','email',
			'description','profileImage','Status','metaTitle','metaDescription',
			'metaKeywords','seo_url','userId','username','planName','firstName','userPlanId',
			'slotTime','slotDate','doctorName','status','dieticianName','bookingStatus','itemName','clinicName','clinicId'], 'safe'],
            [['Name', 'cityName', 'stateName', 'mobilenumber', 'email',
			'profileImage', 'Status', 'metaTitle', 'metaKeywords', 'seo_url', 'ipaddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Name' => 'Name',
            'cityName' => 'City Name',
            'cityId' => 'City',
            'stateId' => 'State',
            'stateName' => 'State Name',
            'mobilenumber' => 'Mobilenumber',
            'email' => 'Email',
            'description' => 'Description',
            'profileImage' => 'Profile Image',
            'Status' => 'Status',
            'metaTitle' => 'Meta Title',
            'metaDescription' => 'Meta Description',
            'metaKeywords' => 'Meta Keywords',
            'seo_url' => 'Seo Url',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipaddress' => 'Ipaddress',
            'userId' => 'User ID',
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
			$user->username = $this->Name;
			$user->email = $this->email;
			$user->mobilenumber = $this->mobilenumber;
			$user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
			$user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
			$user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
			$user->setPassword($this->password);
			$user->status = User::STATUS_ACTIVE;
			$user->roleId = 7;
			$user->roleName = 'Call Centre';
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
	
	public function upcomingdetails($data,$newmodel,$access_token,$event,$doctorname,$dieticanName)	{
		$upcomingdetailsdata = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->asArray()->all();
		$upcomingdata = [];
		if($upcomingdetailsdata != [])
		{
			foreach($upcomingdetailsdata as $key=>$upcomingdetails){
			$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($newmodel->updatedDate)));
			$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($newmodel->updatedDate)));;
			if($upcomingdetails['text'] == 1)
		    {
				$booking = Slotbooking::find()->where(['access_token'=>$access_token])->andWhere(['!=','Status',"Cancel"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
				//$upcomingdetails['text'] = 'Doctor Consultation';
				if(!empty($booking))
				{
						$upcomingdetails['bookingid'] = $booking->bookingId;
				}
				else
				{
					$upcomingdetails['bookingid'] = 0;
					$upcomingdata[$upcomingdetails['text']] ='Doctor Consultation';	
				}	
								
			}
			elseif($upcomingdetails['text'] == 2 && $newmodel->dieticianId != 0)
			{
					
					$booking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andWhere(['!=','Status',"Cancel"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					//$upcomingdetails['text'] = 'Dietician Consultation';
					if(!empty($booking))
					{
						$upcomingdetails['bookingid'] = $booking->bookingId;
					}
					else
					{
						$upcomingdetails['bookingid'] = 0;
						$upcomingdata[$upcomingdetails['text']] =$upcomingdetails['text'];
					}
					
					
			}
			else
			{
				
				$orderbookings = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['orders.access_token'=>$access_token,'orderitems.itemId'=>$upcomingdetails['text']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('orderId DESC')->one();
				//print_r(date('Y-m-d',strtotime($upcomingdetails['day'])));exit;
				if(empty($orderbookings) || ($orderbookings->bookingStatus != 'Reports Generated' ))
				{
					$orderitem = ItemDetails::find()->where(['itemId'=>$upcomingdetails['text']])->one();
					$upcomingdata[$upcomingdetails['text']] =$orderitem->itemName;										 
				}
				
			}
		  }
		}
		//print_r($upcomingdata);exit;;
		return $upcomingdata;
	}
	
	
	public function eventchecking($data,$newmodel,$access_token,$doctorname,$dieticanName)	{
		$doctorbooking = 0;
		$dietbooking = 0;
		$textbooking = 0;
		$upcomingdetails = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->asArray()->one();
		if(!empty($upcomingdetails))
		{
			$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($newmodel->updatedDate)));
			$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($newmodel->updatedDate)));
			
			if(date('Y-m-d',strtotime($upcomingdetails['endday'])) > date('Y-m-d')){
			$completebooking = Slotbooking::find()->where(['access_token'=>$access_token])->andWhere(['Status'=>"Completed"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
			$doctor = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday'],'text'=>1])->andwhere(['planId'=>$newmodel->planId])->asArray()->count();
			if(!empty($completebooking) && $doctor > 0)
			{
				$doctorbooking = 1;	
			}
			$dieticianbooking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andWhere(['Status'=>"Completed"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
		    $diet = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday'],'text'=>2])->andwhere(['planId'=>$newmodel->planId])->asArray()->count();
			
			if(!empty($dieticianbooking) && $diet > 0)
			{
				$dietbooking = 1;
			}
			$orderitemarray = [];
			$orderbookings = Orders::find()->where(['access_token'=>$access_token])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->andwhere(['bookingStatus'=>'Reports Generated'])->orderBy('orderId DESC')->all();
			if(!empty($orderbookings))
			{
				foreach($orderbookings as $orderkey=>$ordervalue)
				{
					$order = Orderitems::find()->where(['orderId'=>$ordervalue->orderId])->one();
                    $orderitem = ItemDetails::find()->where(['itemId'=>$order->itemId])->one();
     				$orderitemarray[] = $orderitem->itemId;
				}
			}
			$textarray[0] =1; 
			$textarray[1] = 2;
			$textarray = array_merge($textarray,$orderitemarray);
			$newtest = Plandetails::find()->where(['NOT IN','text',$textarray])->andwhere(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->count();
			if($newtest == 0)
			{
				$textbooking = 1;
			}
		  }
		  else
			{
					$doctorbooking = 1;
					$dietbooking = 1;
					$textbooking = 1;
			}
		}
		
		$data['doctorbooking'] = $doctorbooking;
		$data['dietbooking'] = $dietbooking;
		$data['textbooking'] = $textbooking;
		//print_r($data);exit;
		return $data;
	}	
	public function upcomingdetailsarray($data,$newmodel,$access_token,$doctorname,$dieticanName)	{
		$upcomingdetailsarray = [];
		$upcomingdetails = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->asArray()->one();
		if(!empty($upcomingdetails))
		{
			$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($newmodel->updatedDate)));
			$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($newmodel->updatedDate)));;
			$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($newmodel->updatedDate)));
			$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($newmodel->updatedDate)));;
			$upcomingdetails['plandetailId'] = strval($upcomingdetails['plandetailId']);				
			$upcomingdetails['planId'] = strval($upcomingdetails['planId']);
			$upcomingdetails['createdBy'] = strval($upcomingdetails['createdBy']);				
			$upcomingdetails['updatedBy'] = strval($upcomingdetails['updatedBy']);
			$duration = date_diff(date_create($upcomingdetails['day']), date_create($upcomingdetails['endday']));
			$upcomingdetails['duration'] = $duration->days;
			$orderbookings = Orders::find()->where(['access_token'=>$access_token])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->andwhere(['bookingStatus'=>'Reports Generated'])->orderBy('orderId DESC')->all();
			$orderitemarray = [];
			if(!empty($orderbookings))
			{
				foreach($orderbookings as $orderkey=>$ordervalue)
				{
					$order = Orderitems::find()->where(['orderId'=>$ordervalue->orderId])->one();
                    $orderitem = ItemDetails::find()->where(['itemId'=>$order->itemId])->one();
     				$orderitemarray[] = $orderitem->itemId;
				}
			}
			$textarray[0] =1; 
			$textarray[1] = 2;
			$textarray = array_merge($textarray,$orderitemarray);
			$newtest = Plandetails::find()->where(['NOT IN','text',$textarray])->andwhere(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->one();
			//print_r($newtest);exit;
			if($newtest!= [])
			{
						$upcomingdetails['text'] =$newtest['text']; 
						$items = explode(',',$newtest['text']);				
						for($i=0;$i<count($items);$i++)
						{
							$text = ItemDetails::find()->where(['itemId'=>$items[$i]])->one();
							$testarray['testId'] =  $items[$i];
							$testarray['testname'] =  $text->itemName;
							$testarray['price'] =  $text->rate;
							$upcomingdetailsarray[] = $testarray;
							
						}
			}
			if($upcomingdetailsarray != [])
			{
						date_default_timezone_set("Asia/Calcutta");
						$query = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['slotDate'=>date('Y-m-d'),'itemId'=>$upcomingdetailsarray[0]['testId'],'orders.access_token'=>$access_token])->one();
				       // print_r($query);exit;
						if(empty($query))
						{
						$ordermodel = new Orders();
						$ordermodel->access_token = $access_token;
						$ordermodel->prebookingId = "46510";
						$ordermodel->bookingStatus = "Offline Booking";
						$ordermodel->slotDate = date('Y-m-d');
						$ordermodel->slotTime = date('H:i');
						$ordermodel->createdDate = date('Y-m-d');
						
						if($ordermodel->save())
						{
								foreach($upcomingdetailsarray as $key=>$value)
								{
									$orderitems = new Orderitems();
									$orderitems->orderId = $ordermodel->orderId;
									$orderitems->itemId = $value['testId'];
									$ordermodel->access_token = $access_token;
									$orderitems->itemName = $value['testname'];
									$orderitems->price = $value['price'];
									$orderitems->createdDate = date('Y-m-d');
									$orderitems->updatedDate = date('Y-m-d');
									$orderitems->save();
								}
						 }
					   }
			}
			$text = ItemDetails::find()->where(['itemId'=>$upcomingdetails['text']])->one()->itemName;
		    $upcomingdetails['text'] = $text;
					
		}
		
		return $upcomingdetailsarray ;
	}
	
	
	
}
