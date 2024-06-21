<?php 
namespace frontend\modules\react\controllers;
use Yii;
use yii\web\Controller;
use backend\models\Appointments;
use backend\models\Appointmentrecords;
use common\models\User;
use frontend\models\Userprofile;
use backend\modules\plans\models\Dietplans;
use backend\modules\plans\models\Dietplandetails;
use backend\modules\plans\models\Excerciseplans;
use backend\modules\plans\models\Excerciseplandetails;
use backend\models\Patientdoctors;
use yii\db\Expression;
use backend\models\Diettrack;
use frontend\models\Glucose;
use backend\modules\users\models\Usermeals;
use frontend\models\Userplans;
use backend\modules\packages\models\Planinclusions;
use backend\modules\packages\models\Plans;
use backend\modules\packages\models\Packages;
use backend\modules\packages\models\Packageitems;
use backend\modules\packages\models\ItemDetails;
use backend\modules\packages\models\Plandetails;
use backend\modules\common\models\Bp;
use backend\modules\webinar\models\Webinars;
use backend\modules\webinar\models\Webinarenrolls;
use backend\modules\common\models\Fooditems;
use backend\modules\users\models\Doctors;
use backend\modules\users\models\Dietician;
use backend\modules\common\models\Portions;
use backend\modules\users\models\Bmivalues;
use backend\modules\users\models\Slots;
use backend\modules\users\models\Dslots;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use backend\modules\users\models\Prescription;
use frontend\models\Prescriptionpdfs;
use frontend\models\Orders;
use frontend\models\Videocalls;
class AppointmentsController extends Controller
{	
	public function actionVideocall()    {
		$data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
        if (Yii::$app->request->post()) 
        {
			$Authorization = Yii::$app->request->headers->get('Authorization');	
			if (empty($Authorization))
			{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
			}
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Videocalls::find()->where(['access_token'=>$post['access_token'],'doctorId'=>$user->id,'createdDate'=>date('Y-m-d')])->one();
				if(empty($model))
				{
					$model = new Videocalls();					 
					$model->doctorId = $user->id;
					$model->access_token =  $post['access_token']; 
					$model->createdDate = date('Y-m-d'); 
					$model->updatedDate = date('Y-m-d');
					if($user->roleId == 3)
					{
					    $doctor = Doctors::find()->where(['userId'=>$user->id])->one();
						$doctorName = $doctor->doctorName;
					}
					elseif($user->roleId == 5)
					{
						$doctor = Dietician::find()->where(['userId'=>$user->id])->one();
						$doctorName = $doctor->dieticianName;
					}
					$url = "https://testapp.apollohl.in/video-conference/meetings/createMeeting";
					$ch = curl_init($url);
					$dataU = array(
					'firstName' => $doctorName,
					'lastName' => '',
					'email' => $doctor->email,
					'mobile' => $doctor->mobilenumber,
					'client' => 'mobile',
					'date' => date('Y-m-d'),
					'time' => date('H:i')
					);
					$payload = json_encode($dataU);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$resultDynamic = json_decode(curl_exec($ch));
					$model->link =$resultDynamic->data->meetingUrl;
					$otp =$resultDynamic->data->meetingUrl.'/7893166416';
					$message = "OTP ".$otp." to login to 7893166416, Apollo Clinic ";
					$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno=7893166416&message='.urlencode($message).'&msgtype=TXT&response=Y';
					$crl = curl_init();
					curl_setopt($crl, CURLOPT_URL, $url);
					curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
					curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
					$res = curl_exec($crl); 
					//print_r($url);exit;
					$model->save();
				}
				return ['status' => true, 'message' => 'Success','link'=>$model->link]; 
	         }
        }
    }
	public static function actionIndex()    {
        $data = array();        
       	$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
			else
			{
		        $patients =  Userprofile::find()->all();
				if(!empty($patients))
				{
						foreach($patients as $key=>$value)
						{							
							$data[$key]['apId'] = $value['profileId'];
							$data[$key]['patId'] = $value['userId'];
							$data[$key]['name'] = $value['firstName'];
							$data[$key]['age'] = $value['age'];
							$data[$key]['gender'] = $value['gender'];
							$date = date('Y-m-01',strtotime(date('Y-m-d')));
							$data[$key]['deviceuserd'] = "";
							$data[$key]['lastreview'] = date('M d,Y',strtotime($date));
							$data[$key]['remarks'] = "Remarks";
							$data[$key]['Status'] = $value['Status'];
						}
				}
				$date = date('Y-m-d');
			    $totalappointments = Userprofile::find()->count();
				$completedappointments = Userprofile::find()->where(['Status'=>"Completed"])->count();;
				$mustconsultappointments = Userprofile::find()->where(['Status'=>"Must Consult"])->count();;
				$pendingappointments = Userprofile::find()->where(['Status'=>"Pending"])->count();;
				$count['totalappointments'] = $totalappointments;
				$count['completedappointments'] = $completedappointments;
				$count['mustconsultappointments'] = $mustconsultappointments;
				$count['Pendingappointments'] = $pendingappointments;
				return ['status' => true, 'message' => 'Success','count'=>[$count],'data' => $data];
			}
	} 
	public static function actionIndexbkp()    {
        $data = array();        
       	$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
			else
			{
		        $patients =  Userprofile::find()->all();
				//print_r($patients);exit;
				if(!empty($patients))
				{
						foreach($patients as $key=>$value)
						{							
							$data[$key]['apId'] = $value['profileId'];
							$data[$key]['patId'] = $value['userId'];
							$data[$key]['name'] = $value['firstName'];
							$data[$key]['age'] = $value['age'];
							$data[$key]['gender'] = $value['gender'];
							$date = date('Y-m-01',strtotime(date('Y-m-d')));
							$data['deviceuserd'] = "";
							$data['lastreview'] = date('M d,Y',strtotime($date));
							$data[$key]['remarks'] = "Remarks";
							$data[$key]['Status'] = $value['Status'];
						}
				}
				$date = date('Y-m-d');
			    $totalappointments = Userprofile::find()->count();
				$completedappointments = Userprofile::find()->where(['Status'=>"Completed"])->count();;
				$mustconsultappointments = Userprofile::find()->where(['Status'=>"Must Consult"])->count();;
				$pendingappointments = Userprofile::find()->where(['Status'=>"Pending"])->count();;
				$count['totalappointments'] = $totalappointments;
				$count['completedappointments'] = $completedappointments;
				$count['mustconsultappointments'] = $mustconsultappointments;
				$count['Pendingappointments'] = $pendingappointments;
				return ['status' => true, 'message' => 'Success','count'=>[$count],'data' => $data];
			}
	}  
    public static function actionView()    {
        $data = array();        
        $get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
			else{
		$value = Appointments::find()->where(['apId'=>$get['apId']])->one();		
		if(!empty($value))
		{			
				$profile = Userprofile::find()->where(['userId'=>$value->patId])->one();
				$data['apId'] = $value->apId;
				$data['patId'] = $value->patId;
				$data['name'] = $profile->firstName.' '.$profile->lastName;
				$data['age'] = $profile->age;
				$data['gender'] = $profile->gender;
				$date = date('Y-m-01',strtotime(date('Y-m-d')));
				$data['deviceuserd'] = "FitBit";
				$data['lastreview'] = date('M d,Y',strtotime($date));
				$data['remarks'] = $value->remarks;
				$patient = Patientdoctors::find()->where(['patientId'=>$value->patId])->one();
				$data['dieticianId'] = $patient->dieticianId;
				$data['dieticianName'] = $patient->dieticianName;
				$data['coachId'] = $patient->coachId;
				$data['coachName'] = $patient->coachName;
				$data['reviewerId'] = $patient->doctorId;
				$data['reviewer'] = $patient->doctorName;
				$appointmentrecords = Appointmentrecords::find()->where(['apId'=>$get['apId']])->orderBy(['apRecordId' => SORT_DESC])->all();
				$obj = new Appointmentrecords();
				$arrFields = array_keys($obj->attributes);
				$patientdata = [];
				for($i=2;$i<=8;$i++)
				{
					$record['from'] = $appointmentrecords[0]->fromdevice;
					if($arrFields[$i] == 'bp')
					{
						$type = 'Blood Pressure';
						$units = '';
					}
					if($arrFields[$i] == 'sugar')
					{
						$type = 'Fasting Sugar';
						$units = 'mg/dl';
					}
					if($arrFields[$i] == 'weight')
					{
						$type = 'Weight';
						$units = 'Kgs';
					}
					if($arrFields[$i] == 'postprandial')
					{
						$type = 'Postparandial';
						$units = 'mg/dl';
					}
					if($arrFields[$i] == 'HbA1c')
					{
						$type = 'HbA1c';
						$units = '';
					}
					if($arrFields[$i] == 'BMI')
					{
						$type = 'BMI';
						$units = '';
					}
					if($arrFields[$i] == 'creatinine')
					{
						$type = 'Creatinine, Serum';
						$units = '';
					}
					$record['testType'] = $type;
					$record['units'] = $units;
					$field = $arrFields[$i];
					$record['status'] = $appointmentrecords[0]->status;
					$record['current'] = $appointmentrecords[0]->$field;
					$record['previous'] = $appointmentrecords[1]->$field;
					$patientdata[] = $record;
				}
				$data['patientdata'] = $patientdata;			
		}
		return ['status' => true, 'message' => 'Success','data' => [$data]];
	  }
	}
	public static function actionDietplans()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
			else
			{

					if(!isset($_GET['fromdate']) || empty($_GET['fromdate']))
					{
						$d = strtotime("today");
						$currentstart_week = strtotime("last sunday midnight",$d);
						$currentend_week = strtotime("next saturday",$d);
						$start = date("Y-m-d",$currentstart_week); 
						$end = date("Y-m-d",$currentend_week);
						$previous_week = strtotime("-1 week +1 day");
						$start_week = strtotime("last sunday midnight",$previous_week);
						$end_week = strtotime("next saturday",$start_week);
						$start_week = date("Y-m-d",$start_week);
						$end_week = date("Y-m-d",$end_week);
						$latestdiet = Dietplans::find()->where(['userId'=>$get['userId']])->orderBy('createdDate DESC')->one();
						//print_r($latestdiet);exit;
						$start_week = $latestdiet->createdDate;
						$end_week = $latestdiet->updatedDate;
					}
					else
					{
						$d = strtotime($_GET['fromdate']);
						$currentstart_week = strtotime("last sunday midnight",$d);
						$currentend_week = strtotime("next saturday",$d);
						$start = date("Y-m-d",$currentstart_week); 
						$end = date("Y-m-d",$currentend_week);
	     				$previous_week = strtotime($start);
						$start_week = strtotime("last sunday midnight",$previous_week);
						$end_week = strtotime("next saturday",$start_week);
						$start_week = date("Y-m-d",$start_week);
						$end_week = date("Y-m-d",$end_week);
						
					}
					//print_r($start);exit;
					$plans = Dietplans::find()->where(['userId'=>$get['userId']])->andwhere(['between', 'createdDate', $start, $end ])->orderBy('planId ASC')->all();
					$newdata = [];
					if(!empty($plans))
					{
						foreach($plans as $key=>$value)
						{
							$data[$key]['time'] = $value->time;
							$data[$key]['type'] = $value->mealtype;
							$items = Dietplandetails::find()->where(['planId'=>$value->planId])->all();
							$data[$key]['items'] = $items;
						}
					}
					$previous_weekplans = Dietplans::find()->where(['userId'=>$get['userId']])->andwhere(['between', 'createdDate', $start_week, $end_week ])->orderBy('planId ASC')->all();
					$previous = [];
					if(!empty($previous_weekplans))
					{
						foreach($previous_weekplans as $x=>$y)
						{
							$previous[$x]['time'] = $y->time;
							$previous[$x]['type'] = $y->mealtype;
							$items = Dietplandetails::find()->where(['planId'=>$y->planId])->all();
							$previous[$x]['items'] = $items;
						}
					}
					$newdata['currentweekdates'] = $start.' - '.$end;
					$newdata['currentweek'] = $data;
					$newdata['previousdates'] = $start_week.' - '.$end_week;
					$newdata['previous'] = $previous;
					return ['status' => true, 'message' => 'Success','data'=>$newdata];
			}		
	}	
	public static function actionExcerciseplans()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
			else{
		$plans = Excerciseplans::find()->where(['userId'=>$get['userId']])->all();
		if(!empty($plans))
		{
			foreach($plans as $key=>$value)
			{
				$data[$key]['time'] = $value->time;
				$items = Excerciseplandetails::find()->where(['explanId'=>$value->explanId])->all();
				foreach($items as $k=>$v)
				{
					$itemlist[$k] = $v['title'].' - '.$v['distance'];
				}
				$data[$key]['items'] = $itemlist;
			}
		}
		return ['status' => true, 'message' => 'Success','data'=>$data];
			}		
	}	
	public static function actionChartdetails()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			if($get['type'] == 'day')
			{
				$records = Appointmentrecords::find()->where(['createdDate'=>date('Y-m-d')])->all();
				$record = Appointmentrecords::find()->where(['createdDate'=>date('Y-m-d')])->average('sugar');
				$previousrecord = Appointmentrecords::find()->where(['createdDate'=>date('Y-m-d', strtotime(' -1 day'))])->average('sugar');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->time;
						$data[$key]['breakfast'] = $value->sugar;
				}				
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$start_week = strtotime("last sunday midnight",$previous_week);
				$end_week = strtotime("next saturday",$start_week);
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d",$end_week);
				$d = strtotime("today");
				$currentstart_week = strtotime("last sunday midnight",$d);
				$currentend_week = strtotime("next saturday",$d);
				$start = date("Y-m-d",$currentstart_week); 
				$end = date("Y-m-d",$currentend_week);
				$records = Appointmentrecords::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->createdDate;
						$recordnew = Appointmentrecords::find()->where(['createdDate'=>$value->createdDate])->average('sugar');
						$data[$key]['breakfast'] = ceil($recordnew);
				}
				$record = Appointmentrecords::find()->where(['between', 'createdDate', $start, $end ])->average('sugar');
				$previousrecord = Appointmentrecords::find()->where(['between', 'createdDate', $start_week, $end_week ])->average('sugar');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');
				$records = Appointmentrecords::find()->select('createdDate')->where(" MONTH( createdDate) = $yourMonth ")->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->createdDate;
						$recordnew = Appointmentrecords::find()->where(['createdDate'=>$value->createdDate])->average('sugar');
						$data[$key]['breakfast'] = ceil($recordnew);
				}
				$record = Appointmentrecords::find()->where(" MONTH( createdDate) = $currentMonth ")->average('sugar');
				$previousrecord = Appointmentrecords::find()->where(" MONTH( createdDate) = $yourMonth ")->average('sugar');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
			}
			if($get['type'] == 'year')
			{
				$yourYear = date('Y') -1;
				$currentYear = date('Y');
				$records = Appointmentrecords::find()->select('MONTH(createdDate)')->where(" Year( createdDate) = $currentYear ")->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->createdDate;
						$recordnew = Appointmentrecords::find()->where(['createdDate'=>$value->createdDate])->average('sugar');
						$data[$key]['breakfast'] = ceil($recordnew);
				}
				$record = Appointmentrecords::find()->where(" Year( createdDate) = $currentYear ")->average('sugar');
				$previousrecord = Appointmentrecords::find()->where(" Year( createdDate) = $yourYear ")->average('sugar');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
			}
			return ['status' => true, 'message' => 'Success','sugardetails'=>[$sugardetails],'data'=>$data];
	   }
	}	
	public static function actionDiettrackchart()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			if($get['type'] == 'day')
			{
				$records = Diettrack::find()->select('mealType')->where(['createdDate'=>date('Y-m-d'),'patId'=>$get['patId']])->distinct('mealType')->orderBy('time ASC')->all();
				$record = Diettrack::find()->where(['createdDate'=>date('Y-m-d'),'patId'=>$get['patId']])->average('cal');
				$previousrecord = Diettrack::find()->where(['createdDate'=>date('Y-m-d', strtotime(' -1 day')),'patId'=>$get['patId']])->average('cal');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
				foreach($records as $key=>$value)
				{
						$time =Diettrack::find()->where(['createdDate'=>date('Y-m-d'),'patId'=>$get['patId'],'mealType'=>$value->mealType])->one();
						$cal = Diettrack::find()->where(['createdDate'=>date('Y-m-d'),'patId'=>$get['patId'],'mealType'=>$value->mealType])->sum('cal');
						$data[$key]['name'] = $time->time;
						$data[$key]['breakfast'] = $cal.'kcal';
				}				
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$start_week = strtotime("last sunday midnight",$previous_week);
				$end_week = strtotime("next saturday",$start_week);
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d",$end_week);
				$d = strtotime("today");
				$currentstart_week = strtotime("last sunday midnight",$d);
				$currentend_week = strtotime("next saturday",$d);
				$start = date("Y-m-d",$currentstart_week); 
				$end = date("Y-m-d",$currentend_week);
				$records = Diettrack::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['patId'=>$get['patId']])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->createdDate;
						$recordnew = Diettrack::find()->where(['createdDate'=>$value->createdDate,'patId'=>$get['patId']])->average('cal');
						$data[$key]['breakfast'] = ceil($recordnew).'kcal';
				}
				$record = Diettrack::find()->where(['between', 'createdDate', $start, $end ])->andwhere(['patId'=>$get['patId']])->average('cal');
				$previousrecord = Diettrack::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['patId'=>$get['patId']])->average('cal');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');
				$records = Diettrack::find()->select('createdDate')->where(" MONTH( createdDate) = $yourMonth ")->andwhere(['patId'=>$get['patId']])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->createdDate;
						$recordnew = Diettrack::find()->where(['createdDate'=>$value->createdDate])->andwhere(['patId'=>$get['patId']])->average('cal');
						$data[$key]['breakfast'] = ceil($recordnew).'kcal';
				}
				$record = Diettrack::find()->where(" MONTH( createdDate) = $currentMonth ")->andwhere(['patId'=>$get['patId']])->average('cal');
				$previousrecord = Diettrack::find()->where(" MONTH( createdDate) = $yourMonth ")->andwhere(['patId'=>$get['patId']])->average('cal');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
			}
			if($get['type'] == 'year')
			{
				$yourYear = date('Y') -1;
				$currentYear = date('Y');
				$records = Diettrack::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['patId'=>$get['patId']])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] = $value->createdDate;
						$recordnew = Diettrack::find()->where(['createdDate'=>$value->createdDate])->andwhere(['patId'=>$get['patId']])->average('cal');
						$data[$key]['breakfast'] = ceil($recordnew);
				}
				$record = Diettrack::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['patId'=>$get['patId']])->average('cal');
				$previousrecord = Diettrack::find()->where(" Year( createdDate) = $yourYear ")->andwhere(['patId'=>$get['patId']])->average('cal');
				$sugardetails['previous'] = ceil($previousrecord);
				$sugardetails['current'] = ceil($record);
			}
			return ['status' => true, 'message' => 'Success','sugardetails'=>[$sugardetails],'data'=>$data];
	   }
	}	
	public static function actionGlucosechart()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$dates = date('Y-m-d');
			if($get['type'] == 'day')
			{
				$averageglucose = ceil(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('glucosevalue'));
				$noonfasting = number_format(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
				$fasting = number_format(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				$morningavg = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealid'=>1 ])->average('glucosevalue');
				$data[0]['name'] = "Break Fast";
				$data[0]['breakfast'] = $morningavg;
				$afternoonavg = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealid'=>2 ])->average('glucosevalue');
				$data[1]['name'] = "Lunch";
				$data[1]['lunch'] = $afternoonavg;
				$dinneravg = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealid'=>3 ])->average('glucosevalue');
				$data[2]['name'] = "Dinner";
				$data[2]['dinner'] = $dinneravg;
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$d = date("l");
				$start_week = strtotime("last ".$d." midnight",$previous_week);				
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d");
				$dates = $start_week.' To '.$end_week;
				$i= 0;
				$averageglucose = ceil(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = number_format(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
			    $fasting = number_format(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningavg = Glucose::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealid'=>1])->average('glucosevalue');
						$afternoonavg = Glucose::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealid'=>2])->average('glucosevalue');
					    $dinneravg = Glucose::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealid'=>3])->average('glucosevalue');
						$data[$i]['breakfast'] = ceil($morningavg);
						$data[$i]['lunch'] = ceil($afternoonavg);
						$data[$i]['dinner'] = ceil($dinneravg);
						$i++;
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($start_week)));
				}
	     	}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');				
				$firstweekstart = date('Y').'-'.$yourMonth.'-'.date('d');
				$firstweekend = date('Y-m-d',strtotime("+7 day", strtotime($firstweekstart)));
				$dates = $firstweekstart.' To '.date('Y-m-d');
				$currentYear = date('Y');
				$currentMonth = date('m');
				$averageglucose = ceil(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
			    $fasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				for($i=0;$i<4;$i++)
				{
					if($i == 0)
					{
						$name = "First Week";
						$start_week = $firstweekstart;
						$end_week = $firstweekend;
					}
					if($i == 1)
					{
						$name = "Second Week";
					}
					if($i == 2)
					{
						$name = "Third Week";
					}
					if($i == 3)
					{
						$name = "Current Week";
					}
					if($i>0)
					{
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($end_week)));
						$end_week = date('Y-m-d',strtotime("+7 day", strtotime($start_week)));
					}
					$data[$i]['name'] = $name;
					$morningavg = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealid'=>1])->average('glucosevalue');
					$afternoonavg = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealid'=>2])->average('glucosevalue');
					$dinneravg = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealid'=>3])->average('glucosevalue');
					$data[$i]['breakfast'] = ceil($morningavg);
					$data[$i]['lunch'] = ceil($afternoonavg);
					$data[$i]['dinner'] = ceil($dinneravg);					
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				$currentYear = date('Y');
				$averageglucose = ceil(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
			    $fasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$morningavg = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealid'=>1])->average('glucosevalue');
						$afternoonavg = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealid'=>2])->average('glucosevalue');
						$dinneravg = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealid'=>3])->average('glucosevalue');
						$data[$i]['breakfast'] = ceil($morningavg);
						$data[$i]['lunch'] = ceil($afternoonavg);
						$data[$i]['dinner'] = ceil($dinneravg);	
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'nonfasting'=>$noonfasting,'fasting'=>$fasting,'dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionMealchart()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$dates = date('Y-m-d');
			$averageglucose = number_format(Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->sum('cal'),2);
			$noonfasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			$fasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
			if($get['type'] == 'day')
			{
				$morningavg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'breakfast' ])->average('cal');
				$data[0]['name'] = "Break Fast";
				$data[0]['breakfast'] = $morningavg;
				$afternoonavg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'lunch' ])->average('cal');
				$data[1]['name'] = "Lunch";
				$data[1]['lunch'] = $afternoonavg;
				$dinneravg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'dinner' ])->average('cal');
				$data[2]['name'] = "Dinner";
				$data[2]['dinner'] = $dinneravg;
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$d = date("l");
				$start_week = strtotime("last ".$d." midnight",$previous_week);				
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d");
				$dates = $start_week.' To '.$end_week;
				$averageglucose = number_format(Usermeals::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->sum('cal'),2);
			   	$noonfasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				$i= 0;
				$averageglucose = 0;
				$divide = 0;
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningavg = Usermeals::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
						$afternoonavg = Usermeals::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
					    $dinneravg = Usermeals::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
						$data[$i]['breakfast'] = ceil($morningavg);
						$data[$i]['lunch'] = ceil($afternoonavg);
						$data[$i]['dinner'] = ceil($dinneravg);
						if(!empty($morningavg) || !empty($afternoonavg) || !empty($dinneravg))
						{
							$divide = $divide + 1;
						}
						$averageglucose = $averageglucose + (Usermeals::find()->where(['createdDate'=>$start_week,'access_token' => $access_token])->sum('cal'));
						$i++;
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($start_week)));
				}
				$averageglucose = number_format(($averageglucose/$divide),2);
				 
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');				
				$firstweekstart = date('Y').'-'.$yourMonth.'-'.date('d');
				$firstweekend = date('Y-m-d',strtotime("+7 day", strtotime($firstweekstart)));
				$dates = $firstweekstart.' To '.date('Y-m-d');
				$currentYear = date('Y');
				$currentMonth = date('m');
				$averageglucose = number_format(Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('cal'),2);
			    $noonfasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				for($i=0;$i<4;$i++)
				{
					if($i == 0)
					{
						$name = "First Week";
						$start_week = $firstweekstart;
						$end_week = $firstweekend;
					}
					if($i == 1)
					{
						$name = "Second Week";
					}
					if($i == 2)
					{
						$name = "Third Week";
					}
					if($i == 3)
					{
						$name = "Current Week";
					}
					if($i>0)
					{
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($end_week)));
						$end_week = date('Y-m-d',strtotime("+7 day", strtotime($start_week)));
					}
					$data[$i]['name'] = $name;
					$morningavg = Usermeals::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
					$afternoonavg = Usermeals::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
					$dinneravg = Usermeals::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
					$data[$i]['breakfast'] = ceil($morningavg);
					$data[$i]['lunch'] = ceil($afternoonavg);
					$data[$i]['dinner'] = ceil($dinneravg);					
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				$currentYear = date('Y');
				$averageglucose = number_format(Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('cal'),2);
			    $noonfasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
		    	for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$morningavg = Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
						$afternoonavg = Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
						$dinneravg = Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
						$data[$i]['breakfast'] = ceil($morningavg);
						$data[$i]['lunch'] = ceil($afternoonavg);
						$data[$i]['dinner'] = ceil($dinneravg);	
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'dates'=>$dates,'data'=>$data];
	   }
	}
	public function actionUserplandetails()	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$data = array();
		$consultations = array();
        $get = Yii::$app->request->get();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }       
        $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
				$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
                $newmodel = Userplans::find()->where(['access_token' =>$access_token])->one();
				$planname = Plans::find()->where(['planId'=>$newmodel->planId])->one()->PlanName;
				if(!empty($newmodel))
				{
					$model = Planinclusions::find()->where(['planId'=>$newmodel->planId])->orderBy('planIncId DESC')->all();
					if(!empty($model))
					{						
							foreach($model as $key=>$value)
							{
								$data[$key]['planname'] = $value->packageName;
								$inclusions = Packageitems::find()->where(['packageId'=>$value->packageId])->all();
								foreach($inclusions as $k=>$v)
								{
									$items[$k] = $v->itemName;
								}	
								$data[$key]['inclusions'] = $items;							
							}
					}
					$details = Plandetails::find()->where(['planId'=>$newmodel->planId])->all();
					if($details != []){
					{
							foreach($details as $x=>$v)
							{
								if($v['text'] == 1)
								{
									$consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
									$consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));;
								    $text = ItemDetails::find()->where(['itemId'=>$v['text']])->one()->itemName;
				                    $consultations[$x]['text'] = $text;
									$consultations[$x]['filetype'] = "Prescription";
									$startdate = date('Y-m-d',strtotime($consultations[$x]['day']));
									$enddate = date('Y-m-d',strtotime($consultations[$x]['endday']));
									$Prescription = Prescription::find()->where(['between','createdDate',$startdate,$enddate])->andwhere(['access_token'=>$access_token])->one();
									$filename = '';
									if(!empty($Prescription))
									{
									   $Prescriptionpdfs = Prescriptionpdfs::find()->where(['prescriptionId'=>$Prescription->prescriptionId])->one();
									   $filename = Yii::$app->request->hostInfo.'/ApolloSugar/'.$Prescriptionpdfs->fileName;
									   $consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($Prescription->createdDate)));
									   $consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($Prescription->createdDate)));
									}									
									$consultations[$x]['filename'] = $filename;
								}
								elseif($v['text'] == 2)
								{
									$consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));;
									$consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));;
									$text = ItemDetails::find()->where(['itemId'=>2])->one()->itemName;
									$consultations[$x]['text'] = $text;
									$consultations[$x]['filetype'] = "Diet Plan";
									if(date('Y-m-d',strtotime($consultations[$x]['day'])) < date('Y-m-d'))
									{
										$consultations[$x]['filename'] = "https://devapp.apollohl.in:8443/ApolloSugar/frontend/web/index.php?r=react/appointments/dietplans&userId=".$get['patId'];
									}
									else
									{
										$consultations[$x]['filename'] = "";
									}
								}
								else
								{
									$consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));;
									$consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));;
									$teststartdate = date('Y-m-d',strtotime($consultations[$x]['day']));
									$testenddate = date('Y-m-d',strtotime($consultations[$x]['endday']));
									$orders = Orders::find()->where(['between','createdDate',$startdate,$enddate])->andwhere(['access_token'=>$access_token])->one();
									
									$items = explode(',',$v['text']);
									$text = [];
									for($i=0;$i<count($items);$i++)
									{
										$text[] = ItemDetails::find()->where(['itemId'=>$items[$i]])->one()->itemName;
									}
									$consultations[$x]['filename'] = "";
									if(!empty($orders))
									{
									   $consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($orders->createdDate)));
									   $consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($orders->createdDate)));
									   $consultations[$x]['filename'] = "https://report.apollodiagnostics.in/Apollo/Design/Lab/PatientReceiptNew.aspx?";
									}
									$consultations[$x]['text'] = implode(',',$text);
									$consultations[$x]['filetype'] = "Reports";
									
								}
								if($v['date'] < date('Y-m-d'))
								{
								   $consultations[$x]['status'] = 1;
								}
								else
								{
									 $consultations[$x]['status'] = 0;
								}
							}							
					}			
				}
                return ['status' => true, 'message' => 'Plans', 'plan'=>$planname,'inclusions' => $data,'consultations'=>$consultations]; 
            }  
	}	
	}
	public function actionMeallog()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$morningvalues =  usermeals::find()->where(['access_token' => $access_token,'createdDate'=>date('Y-m-d'),'mealtype'=>'breakfast'])->asArray()->all();	
			$morningavg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
			$data[0]['name'] = 'Break Fast';
			$data[0]['breakfast'] = $morningavg;
			$morningdata = [];
			if($morningvalues  != [])
			{
				foreach($morningvalues as $mk=>$mv)
				{
					$item = Fooditems::find()->where(['itemId'=>$mv['itemId']])->one();
					$mv['itemName'] = $item->itemName;
					$morningdata[] = $mv;
				}
			}
			$data[0]['meals'] = $morningdata;
			
			$lunchvalues =  usermeals::find()->where(['access_token' => $access_token,'createdDate'=>date('Y-m-d'),'mealtype'=>'lunch'])->asArray()->all();	
			$lunchavg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
			$data[1]['name'] = 'Lunch';
			$data[1]['breakfast'] = $lunchavg;
			$lunchdata = [];
			if($lunchvalues  != [])
			{
				foreach($lunchvalues as $lk=>$lv)
				{
					$item = Fooditems::find()->where(['itemId'=>$lv['itemId']])->one();
					$lv['itemName'] = $item->itemName;
					$lunchdata[] = $lv;
				}
			}
			$data[1]['meals'] = $lunchdata;			
			$dinnervalues =  usermeals::find()->where(['access_token' => $access_token,'createdDate'=>date('Y-m-d'),'mealtype'=>'dinner'])->asArray()->all();	
			$dinneravg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
			$data[2]['name'] = 'Dinner';
			$data[2]['breakfast'] = $dinneravg;
			$dinnerdata = [];
			if($dinnervalues  != [])
			{
				foreach($dinnervalues as $dk=>$dv)
				{
					$item = Fooditems::find()->where(['itemId'=>$dv['itemId']])->one();
					$dv['itemName'] = $item->itemName;
					$dinnerdata[] = $dv;
				}
			}
			$data[2]['meals'] = $dinnerdata;			
			return ['status' => true, 'message' => 'Success','date'=>date('Y-m-d'),'data'=>$data];
	   }		
	}	
	public function actionUserMealTrack()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
		   $newdata = array();
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$values =  usermeals::find()->select('createdDate')->where(['access_token' => $access_token])->orderBy([
            'createdDate' => SORT_DESC])->distinct()->asArray()->all();	
			foreach($values as $key=>$value){
				$data = [];
		    $morningvalues =  usermeals::find()->where(['access_token' => $access_token,'createdDate'=>$value['createdDate'],'mealtype'=>'breakfast'])->asArray()->all();	
			$morningavg = Usermeals::find()->where(['createdDate'=>$value['createdDate'],'access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
			$data[0]['name'] = 'Break Fast';
			$data[0]['breakfast'] = $morningavg;
			$morningdata = [];
			if($morningvalues  != [])
			{
				foreach($morningvalues as $mk=>$mv)
				{
					$item = Fooditems::find()->where(['itemId'=>$mv['itemId']])->one();
					$mv['itemName'] = $item->itemName;
					$morningdata[] = $mv;
				}
			}
			$data[0]['meals'] = $morningdata;			
			$lunchvalues =  usermeals::find()->where(['access_token' => $access_token,'createdDate'=>$value['createdDate'],'mealtype'=>'lunch'])->asArray()->all();	
			$lunchavg = Usermeals::find()->where(['createdDate'=>$value['createdDate'],'access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
			$data[1]['name'] = 'Lunch';
			$data[1]['breakfast'] = $lunchavg;
			$lunchdata = [];
			if($lunchvalues  != [])
			{
				foreach($lunchvalues as $lk=>$lv)
				{
					$item = Fooditems::find()->where(['itemId'=>$lv['itemId']])->one();
					$lv['itemName'] = $item->itemName;
					$lunchdata[] = $lv;
				}
			}
			$data[1]['meals'] = $lunchdata;			
			$dinnervalues =  usermeals::find()->where(['access_token' => $access_token,'createdDate'=>$value['createdDate'],'mealtype'=>'dinner'])->asArray()->all();	
			$dinneravg = Usermeals::find()->where(['createdDate'=>$value['createdDate'],'access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
			$data[2]['name'] = 'Dinner';
			$data[2]['breakfast'] = $dinneravg;
			$dinnerdata = [];
			if($dinnervalues  != [])
			{
				foreach($dinnervalues as $dk=>$dv)
				{
					$item = Fooditems::find()->where(['itemId'=>$dv['itemId']])->one();
					$dv['itemName'] = $item->itemName;
					$dinnerdata[] = $dv;
				}
			}
			    $data[2]['meals'] = $dinnerdata;
				$newdata[$key]['date'] = $value['createdDate'];
				$newdata[$key]['data'] = $data;
			}
			return ['status' => true, 'message' => 'Success','data'=>$newdata];
	   }		
	}	
	public function actionDashboardbkpnew()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
		    $totalpatientsarray = [];
			$mustobservearray = [];
			$topperformarray = [];
			$lowperformarray = [];
			$webinardata = [];
			$futurewebinars = [];
			$topperformcount = 0;
			$mustobservecount = 0;
			$lowperformcount = 0;
			if($user->roleId == 3)
			{
				$userplans = userplans::find()->where(['doctorId'=>$user->id,'Status'=>'Active'])->orderBy('userPlanId DESC')->asArray()->all();
			}
            elseif($user->roleId == 5)
			{
				$userplans = userplans::find()->where(['dieticianId'=>$user->id,'Status'=>'Active'])->orderBy('userPlanId DESC')->asArray()->all();
			}
			foreach($userplans as $key=>$value)
			{
				$totalpatientsarray[$key] = view($value['access_token']);
				$date = date('Y-m-d',strtotime("-1 days"));
				$sql = "select distinct(access_token)  FROM glucose where access_token='".$value['access_token']."' AND ((createdDate in ('".$date."','".date('Y-m-d')."')))";
				//print_r($sql);exit;
				$observation = Glucose::findBySql($sql)->asArray()->one();
				$lowperformsql = "select distinct(g.access_token)  FROM glucose as g INNER JOIN bp as b ON b.access_token = g.access_token where g.access_token='".$value['access_token']."' AND ((glucosevalue < 300) AND (200 < glucosevalue && (b.SystolicValue > 160 OR b.DiastolicValue > 100)))";
				$lowperform = Glucose::findBySql($lowperformsql)->asArray()->one();
				if(empty($observation))
				{
					
					$mustobservecount = $mustobservecount + 1;
					$mustobservearray[] = $this->view($value['access_token']);
				}
				else if(!empty($lowperform))
				{
					$lowperformcount = $lowperformcount + 1;
					$lowperformarray[] = $this->view($value['access_token']);
				}
				else
				{
					$topperformcount = $topperformcount + 1;
					$topperformarray[] = $this->view($value['access_token']);
				}
			}
			$data[0]['title'] = 'Total Patients';
			$data[0]['value'] = count($userplans);
			$data[1]['title'] = 'Adherence';
			$data[1]['value'] = $topperformcount;
			$data[2]['title'] = 'Non Adherence';
			$data[2]['value'] = $mustobservecount;
			$data[3]['title'] = 'Urgent Attention';
			$data[3]['value'] = $lowperformcount;
			if($user->roleName == "Doctor")
			{
				$doctor = Doctors::find()->where(['userId'=>$user->id])->one()->userId;
				$webinars = Webinars::find()->where(['doctorId'=>$doctor])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->count();
				$Webinarenrolls = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->count();
				$joined = 0;
				$converted = 0;			
				$webinardata[0]['title'] = 'Past Webinars';
				$webinardata[0]['value'] = $webinars;
				$webinardata[1]['title'] = 'Joined';
				$webinardata[1]['value'] = $joined;
				$webinardata[2]['title'] = 'Converted';
				$webinardata[2]['value'] = $converted;
				$futurewebinarscount = Webinars::find()->where(['doctorId'=>$doctor])->andwhere(['>=','webinars.createdDate',date('Y-m-d')])->count();
				$futurewebinars[0]['title'] = 'Upcoming Webinars';
				$futurewebinars[0]['value'] = $futurewebinarscount;
				$futurewebinars[1]['title'] = 'Enrolled';
				$futurewebinars[1]['value'] = $Webinarenrolls;
			}
			return ['status' => true, 'message' => 'Success','data'=>$data,'Futurewebinars'=>$futurewebinars,'totalpatients'=>$totalpatientsarray,
			'mustobservearray'=>$mustobservearray,'topperformarray'=>$topperformarray,
			'lowperformarray'=>$lowperformarray,'webinardata'=>$webinardata];
	   }		
	}
	public function actionDashboard()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
		    $totalpatientsarray = [];
			$mustobservearray = [];
			$topperformarray = [];
			$lowperformarray = [];
			$webinardata = [];
			$futurewebinars = [];
			$topperformcount = 0;
			$mustobservecount = 0;
			$lowperformcount = 0;
			$count = [];
			$piechart = [];
			$expired = 0;
			$subscription = 0;
			$referralamount = 0;
			$consultationfee = 0;
			if($user->roleId == 3)
			{
				$userplans = userplans::find()->where(['doctorId'=>$user->id])->orderBy('userPlanId DESC')->asArray()->all();
				$expired = userplans::find()->where(['doctorId'=>$user->id,'Status'=>'Expired'])->count();
				$subscription = userplans::find()->where(['doctorId'=>$user->id,'Status'=>'Subcribed'])->count();
			}
            elseif($user->roleId == 5)
			{
				$userplans = userplans::find()->where(['dieticianId'=>$user->id])->orderBy('userPlanId DESC')->asArray()->all();
				$expired = userplans::find()->where(['dieticianId'=>$user->id,'Status'=>'Expired'])->count();
				$subscription = userplans::find()->where(['dieticianId'=>$user->id,'Status'=>'Subcribed'])->count();
			}
			$femalecount = 0;
			$malecount = 0;
			foreach($userplans as $key=>$value)
			{
				$totalpatientsarray[$key] = $this->view($value['access_token']);
				$date = date('Y-m-d',strtotime("-1 days"));
				$sql = "select distinct(access_token)  FROM glucose where access_token='".$value['access_token']."' AND ((createdDate in ('".$date."','".date('Y-m-d')."')))";
				//print_r($sql);exit;
				$observation = Glucose::findBySql($sql)->asArray()->one();
				$lowperformsql = "select distinct(g.access_token)  FROM glucose as g INNER JOIN bp as b ON b.access_token = g.access_token where g.access_token='".$value['access_token']."' AND ((glucosevalue < 300) AND (200 < glucosevalue && (b.SystolicValue > 160 OR b.DiastolicValue > 100)))";
				$lowperform = Glucose::findBySql($lowperformsql)->asArray()->one();
				if(empty($observation))
				{					
					$mustobservecount = $mustobservecount + 1;
					$mustobservearray[] = $this->view($value['access_token']);
				}
				else if(!empty($lowperform))
				{
					$lowperformcount = $lowperformcount + 1;
					$lowperformarray[] = $this->view($value['access_token']);
				}
				else
				{
					$topperformcount = $topperformcount + 1;
					$topperformarray[] = $this->view($value['access_token']);
				}
				$usernew = User::find()->where(['access_token'=>$value['access_token']])->one();
		        $profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();	
				if($profile->gender == 'Female')
				{
					$femalecount = $femalecount + 1;
				}
				else
				{
					$malecount = $malecount + 1;
				}
				$referralamount = $referralamount + $value['referralamount'];
				if($user->roleId == 3)
				{
					$Plandetails = Plandetails::find()->where(['planId'=>$value['planId'],'text'=>1])->count();
					$item = ItemDetails::find()->where(['itemId'=>1])->one();
					$consultationfee = $consultationfee + $item->rate;
				}
				if($user->roleId == 5)
				{
					$Plandetails = Plandetails::find()->where(['planId'=>$value['planId'],'text'=>2])->count();
					$item = ItemDetails::find()->where(['itemId'=>2])->one();
					$consultationfee = $consultationfee + $item->rate;
				}
			}
			
			$data[0]['title'] = 'Non Adherence';
			$data[0]['value'] = $mustobservecount;
			$data[1]['title'] = 'Urgent Attention';
			$data[1]['value'] = $lowperformcount;
			$data[2]['title'] = 'Adherence';
			$data[2]['value'] = $topperformcount;		
			$count[0]['title'] = 'Total Patients';
			$count[0]['value'] = count($userplans);
			$count[1]['title'] = 'Subscribed';
			$count[1]['value'] = $subscription;
			$count[2]['title'] = 'Subscription Expired';
			$count[2]['value'] = $expired;
			$piechart[0]['name']="Diagnostics commission";
			$piechart[0]['value']=0;
			$piechart[1]['name']= "Consultation fee";
			$piechart[1]['value']= $consultationfee;
			$piechart[2]['name']= "Referral bonus";
			$piechart[2]['value']= $referralamount;
			 return ['status' => true, 'message' => 'Success','data'=>$data,'count'=>$count,'piechart'=>$piechart,'totalpatients'=>$totalpatientsarray,
			'nonAdherence'=>$mustobservearray,'adherence'=>$topperformarray,
			'urgentAttention'=>$lowperformarray];
	   }		
	}
	public function actionPatientsbystatus()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
		    $totalpatientsarray = [];
			if($user->roleId == 3)
			{
				$userplans = userplans::find()->where(['doctorId'=>$user->id,'Status'=>$_GET['Status']])->orderBy('userPlanId DESC')->asArray()->all();
			}
            elseif($user->roleId == 5)
			{
				$userplans = userplans::find()->where(['dieticianId'=>$user->id,'Status'=>$_GET['Status']])->orderBy('userPlanId DESC')->asArray()->all();
			}
			$femalecount = 0;
			$malecount = 0;
			foreach($userplans as $key=>$value)
			{
				
				$totalpatientsarray[$key] = $this->viewstatus($value['access_token'],$_GET['Status']);
			}
		    return ['status' => true, 'message' => 'Success','data'=>$totalpatientsarray];
	   }		
	}
	public function actionWebinars()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
		   	$doctor = Doctors::find()->where(['userId'=>$user->id])->one();
			if(!empty($doctor))
			{
				$doctor = Doctors::find()->where(['userId'=>$user->id])->one()->userId;
				$webinars = Webinars::find()->where(['doctorId'=>$doctor,'Status'=>"Active"])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->count();
				$Webinarenrolls = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id,'Status'=>"Active"])->andwhere(['>=','webinars.createdDate',date('Y-m-d')])->count();
				$futurewebinarscount = Webinars::find()->where(['doctorId'=>$doctor,'Status'=>"Active"])->andwhere(['>=','webinars.createdDate',date('Y-m-d')])->count();
				$Advance = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id,'Status'=>"Active"])->andwhere(['>=','webinars.createdDate',date('Y-m-d')])->all();
			    $joinedarray = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->all();
				$newwebinars = Webinars::find()->where(['doctorId'=>$doctor])->andwhere(['Status'=>"Active"])->all();
				$joinedcount = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->count();
			}
			else
			{
				$webinars = Webinars::find()->where(['<','webinars.createdDate',date('Y-m-d')])->andwhere(['Status'=>"Active"])->count();
				$Webinarenrolls = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['>=','webinars.createdDate',date('Y-m-d')])->andwhere(['Status'=>"Active"])->count();
				$futurewebinarscount = Webinars::find()->where(['>=','webinars.createdDate',date('Y-m-d')])->count();
				$Advance = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['>=','webinars.createdDate',date('Y-m-d')])->andwhere(['Status'=>"Active"])->all();
				$joinedarray = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['<','webinars.createdDate',date('Y-m-d')])->all();
			    $newwebinars = Webinars::find()->where(['Status'=>"Active"])->all();
				$joinedcount = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['<','webinars.createdDate',date('Y-m-d')])->count();
			
			}			
			$joined = 0;
			$converted = 0;
			$data[0]['title'] = 'Advance Enrollment';
			$data[0]['value'] = $Webinarenrolls;
			$data[1]['title'] = 'Joined';
			$data[1]['value'] = $joinedcount;
			$data[2]['title'] = 'Converted';
			$data[2]['value'] = 0;			
			$futurewebinars[0]['title'] = 'Completed';
			$futurewebinars[0]['value'] = $webinars;	
			$futurewebinars[1]['title'] = 'Upcoming';
			$futurewebinars[1]['value'] = $futurewebinarscount;		    
			$femalecount = 0;
			$malecount = 0;
			$advanceEnrolled = [];
			$joined = [];
			$converted = [];
			foreach($Advance as $key=>$value)
			{
				$advanceEnrolled[$key] = $this->view($value['access_token']);
				//print_r($totalpatientsarray);exit;
				$usernew = User::find()->where(['access_token'=>$value['access_token']])->one();
		        $profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();	
				if($profile->gender == 'Female')
				{
					$femalecount = $femalecount + 1;
				}
				else
				{
					$malecount = $malecount + 1;
				}
			}
			foreach($joinedarray as $jkey=>$jvalue)
			{
				$joined[$jkey] = $this->view($jvalue['access_token']);
				$usernew = User::find()->where(['access_token'=>$jvalue['access_token']])->one();
		        $profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();	
				if($profile->gender == 'Female')
				{
					$femalecount = $femalecount + 1;
				}
				else
				{
					$malecount = $malecount + 1;
				}
			}
			$piechart[0]['name']="Female";
			$piechart[0]['value']=$femalecount;
			$piechart[1]['name']="Male";
			$piechart[1]['value']=$malecount;			
			$Webinararray = [];
			foreach($newwebinars  as $wkey=>$wvalue)
			{
				if($wvalue->createdDate >= date('Y-m-d'))
				{
					$status = 'Upcoming';
				}
				else
				{
					$status = 'Completed';
				}
				$wvalue->Status = $status;
				$Webinararray[$wkey] = $wvalue;
			}		
			return ['status' => true, 'message' => 'Success','data'=>$data,'count'=>$futurewebinars,
		'piechart'=>$piechart,'advanceEnrolled'=>$advanceEnrolled,'joined'=>$joined,'converted'=>$converted,'totalWebinars'=>$Webinararray];
	   }		
	}	
	public function actionSubscribedprograms()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {		
			if($user->roleId == 3)
			{
				$programssql = "select plans.planId,count(*) as total FROM plans LEFT JOIN userplans ON plans.planId = userplans.planId where plans.doctorId=".$user->id." GROUP BY userplans.planId ORDER BY count(*) DESC LIMIT 5";
			}
			elseif($user->roleId == 5)
			{
				$programssql = "select  planId,count(*) as total  FROM userplans where dieticianId=".$user->id." GROUP BY planId ORDER BY count(*) DESC LIMIT 5";
			}
			$model = Userplans::findBySql($programssql)->asArray()->all();
			$data = [];
			if($model != [])
			{
				foreach($model as $key=>$value)
				{
					$plan = Plans::find()->where(['planId'=>$value['planId']])->one();
					if($user->roleId == 3)
			        {
						$malecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Male','doctorId'=>$user->id])->count();
						$femalecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Female','doctorId'=>$user->id])->count();
					}
					elseif($user->roleId == 5)
					{
						$malecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Male','dieticianId'=>$user->id])->count();
						$femalecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Female','dieticianId'=>$user->id])->count();
					}
					//$value['total'] = $femalecountssql + $malecountssql;
					$data[$key]['total'] = $value['total'];
					$data[$key]['female'] = $femalecountssql;
					$data[$key]['male'] = $malecountssql;
					$data[$key]['name'] = $plan->PlanName;					
				}
			}	
			
			return ['status' => true, 'message' => 'Success','data'=>$data];
	   }		
	}
	public function actionTopsubscribedprograms()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {		
			if($user->roleId == 3)
			{
				$programssql = "select planId,count(*) as total  FROM userplans where doctorId=".$user->id." GROUP BY planId ORDER BY count(*) DESC LIMIT 5";
			}
			elseif($user->roleId == 5)
			{
				$programssql = "select  planId,count(*) as total  FROM userplans where dieticianId=".$user->id." GROUP BY planId ORDER BY count(*) DESC LIMIT 5";
			}
			$model = Userplans::findBySql($programssql)->asArray()->all();
			$data = [];
			if($model != [])
			{
				foreach($model as $key=>$value)
				{
					$plan = Plans::find()->where(['planId'=>$value['planId']])->one();
					if($user->roleId == 3)
			        {
						$malecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Male','doctorId'=>$user->id])->count();
						$femalecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Female','doctorId'=>$user->id])->count();
					}
					elseif($user->roleId == 5)
					{
						$malecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Male','dieticianId'=>$user->id])->count();
						$femalecountssql =  Userplans::find()->innerJoin('userprofile', 'userprofile.access_token = userplans.access_token')->where(['planId'=>$value['planId'],'gender'=>'Female','dieticianId'=>$user->id])->count();
					}
					$data[$key]['total'] = $value['total'];
					$data[$key]['female'] = $femalecountssql;
					$data[$key]['male'] = $malecountssql;
					$data[$key]['name'] = $plan->PlanName;					
				}
			}			
			return ['status' => true, 'message' => 'Success','data'=>$data];
	   }		
	}
	public function actionPlanstatusupdate()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {		
			$userplan = Userplans::find()->where(['access_token'=>$_GET['access_token'],'planId'=>$_GET['planId']])->one();
			$userplan->Status = "Subcribed";
			$userplan->save();
			return ['status' => true, 'message' => 'Success'];
	   }		
	}	
	public function view($access_token)	{
		$data = [];
	    $usernew = User::find()->where(['access_token'=>$access_token])->one();
		$userplan = Userplans::find()->where(['access_token'=>$access_token])->one();
		$profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();	
		if(empty($profile))
		{
			print_r($access_token);exit;
		}
        $data['patId'] = $profile['userId'];
		$data['access_token'] = $profile['access_token'];
		if(empty($userplan))
		{
			$data['planId'] = 0;
		}
		else
		{
			$data['planId'] = $userplan->planId;
		}
		$data['name'] = $profile['firstName'];			
		$data['age'] = $profile['age'];
		$data['mobilenumber']=$usernew->username;
		$data['gender'] = $profile['gender'];	
		$date = date('Y-m-01',strtotime(date('Y-m-d')));
		$data['deviceuserd'] = "";
		$data['lastreview'] = date('M d,Y',strtotime($date));
		$statusarray[0]['key'] = 'Avg Glucose';
		$date = date('Y-m-d');
		$subscriptionstatus = userplans::find()->where(['access_token'=>$access_token])->one();
		if($subscriptionstatus)
		{
		if($subscriptionstatus->Status == 'Subcribed')
		{
			$data['subscriptionstatus'] = 'Subscribed';
		}
		else
		{
			$data['subscriptionstatus'] = $subscriptionstatus->Status;
		}	
		}
		else
		{
			$data['subscriptionstatus'] = "";
		}
		$avg = Glucose::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
		if(empty($avg))
		{
				$statusarray[0]['value'] = 0;
		}
		else
		{
				$statusarray[0]['value'] = 1;
		}
		$statusarray[0]['status'] = 1;
		$mealavg = Usermeals::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
		$statusarray[1]['key'] = 'Meal Log';
		if(empty($mealavg))
		{
				$statusarray[1]['value'] = 0;
		}
		else
		{
				$statusarray[1]['value'] = 1;
		}
		$statusarray[1]['status'] = 0;
		$statusarray[2]['key'] = 'Blood Pressure';
		$bpavg = Bp::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
		if(empty($bpavg))
		{
			$statusarray[2]['value'] = 0;
		}
		else
		{
			$statusarray[2]['value'] = 1;
		}
		$statusarray[2]['status'] = 1;
		$data['status'] = $statusarray;
		return $data;		
	}
	public function viewstatus($access_token,$status)	{
		$data = [];
	    $usernew = User::find()->where(['access_token'=>$access_token])->one();
		$userplan = Userplans::find()->where(['access_token'=>$access_token,'Status'=>$status])->one();
		$profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();	
		if(empty($profile))
		{
			print_r($access_token);exit;
		}
        $data['patId'] = $profile['userId'];
		$data['access_token'] = $profile['access_token'];
		if(empty($userplan))
		{
			$data['planId'] = 0;
		}
		else
		{
			$data['planId'] = $userplan->planId;
		}
		$data['name'] = $profile['firstName'];			
		$data['age'] = $profile['age'];
		$data['mobilenumber']=$usernew->username;
		$data['gender'] = $profile['gender'];	
		$date = date('Y-m-01',strtotime(date('Y-m-d')));
		$data['deviceuserd'] = "";
		$data['lastreview'] = date('M d,Y',strtotime($date));
		$statusarray[0]['key'] = 'Avg Glucose';
		$date = date('Y-m-d');
		$subscriptionstatus = userplans::find()->where(['access_token'=>$access_token,'Status'=>$status])->one();
		if($subscriptionstatus)
		{
		if($subscriptionstatus->Status == 'Subcribed')
		{
			$data['subscriptionstatus'] = 'Subscribed';
		}
		else
		{
			$data['subscriptionstatus'] = $subscriptionstatus->Status;
		}	
		}
		else
		{
			$data['subscriptionstatus'] = "";
		}
		$avg = Glucose::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
		if(empty($avg))
		{
				$statusarray[0]['value'] = 0;
		}
		else
		{
				$statusarray[0]['value'] = 1;
		}
		$statusarray[0]['status'] = 1;
		$mealavg = Usermeals::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
		$statusarray[1]['key'] = 'Meal Log';
		if(empty($mealavg))
		{
				$statusarray[1]['value'] = 0;
		}
		else
		{
				$statusarray[1]['value'] = 1;
		}
		$statusarray[1]['status'] = 0;
		$statusarray[2]['key'] = 'Blood Pressure';
		$bpavg = Bp::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
		if(empty($bpavg))
		{
			$statusarray[2]['value'] = 0;
		}
		else
		{
			$statusarray[2]['value'] = 1;
		}
		$statusarray[2]['status'] = 1;
		$data['status'] = $statusarray;
		return $data;		
	}
	public function actionDashboardBkp()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$patients =  Userprofile::find()->all();
			$totalpatients = count($patients);
			$topperformcount = 0;
			$mustobservecount = 0;
			$lowperformcount = 0;
			$lowperformarray = [];
			$topperformarray = [];
			$mustobservearray = [];
			$lowperformdata = [];
			$mustobservedata = [];
			$topperformdata = [];
			$data = array();
			$totalpatientsarray = [];
			foreach($patients  as $key=>$value){
				$statusarray = [];
				$access_token = User::find()->where(['id' => $value['userId']])->one()->access_token;
				$topperform = Glucose::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
				$totalpatientsarray[$key]['patId'] = $value['userId'];
				$totalpatientsarray[$key]['name'] = $value['firstName'];			
				$totalpatientsarray[$key]['age'] = $value['age'];
				$totalpatientsarray[$key]['gender'] = $value['gender'];
				$date = date('Y-m-01',strtotime(date('Y-m-d')));
				$totalpatientsarray[$key]['deviceuserd'] = "";
				$totalpatientsarray[$key]['lastreview'] = date('M d,Y',strtotime($date));
				$statusarray[0]['key'] = 'Avg Glucose';
					$avg = Glucose::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					if(empty($avg))
					{
						$statusarray[0]['value'] = 0;
					}
					else
					{
						$statusarray[0]['value'] = 1;
					}
					$statusarray[0]['status'] = 1;
					$mealavg = Usermeals::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					$statusarray[1]['key'] = 'Meal Log';
					if(empty($mealavg))
					{
						$statusarray[1]['value'] = 0;
					}
					else
					{
						$statusarray[1]['value'] = 1;
					}
					$statusarray[1]['status'] = 0;
					$statusarray[2]['key'] = 'Blood Pressure';
					$bpavg = Bp::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					if(empty($bpavg))
					{
						$statusarray[2]['value'] = 0;
					}
					else
					{
						$statusarray[2]['value'] = 1;
					}
					$statusarray[2]['status'] = 1;
					$totalpatientsarray[$key]['status'] = $statusarray;
				if($topperform)
				{
					$topperformcount = $topperformcount + 1;
					$statusarray = [];
					$top = Userprofile::find()->where(['userId'=>'2'])->one();
					$topperformdata['patId'] =  $top['userId'];
					$topperformdata['name'] =  $top['firstName'];
					$topperformdata['age'] = $top['age'];
				    $topperformdata['gender'] = $top['gender'];
					$date = date('Y-m-01',strtotime(date('Y-m-d')));
					$topperformdata['deviceuserd'] = "";
				    $topperformdata['lastreview'] = date('M d,Y',strtotime($date));
					$statusarray[0]['key'] = 'Avg Glucose';
					$avg = Glucose::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					if(empty($avg))
					{
						$statusarray[0]['value'] = 0;
					}
					else
					{
						$statusarray[0]['value'] = 1;
					}
					$statusarray[0]['status'] = 1;
					$mealavg = Usermeals::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					$statusarray[1]['key'] = 'Meal Log';
					if(empty($mealavg))
					{
						$statusarray[1]['value'] = 0;
					}
					else
					{
						$statusarray[1]['value'] = 1;
					}
					$statusarray[1]['status'] = 0;
					$statusarray[2]['key'] = 'Blood Pressure';
					$bpavg = Bp::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					if(empty($bpavg))
					{
						$statusarray[2]['value'] = 0;
					}
					else
					{
						$statusarray[2]['value'] = 1;
					}
					$statusarray[2]['status'] = 1;
					$topperformdata['status'] = $statusarray;
					$topperformarray[0] = $topperformdata;			
				}
				$mustobserve= Glucose::find()->where(['access_token'=>"seUBkt9uu_zopYFDAOuAYnWGBOvDKlaA2tTryijm"])->one();
				if($mustobserve)
				{
					$mustobservecount =  1;
					$statusarray = [];
					$low = Userprofile::find()->where(['userId'=>"30"])->one();
					$mustobservedata['patId'] =  $low['userId'];
					$mustobservedata['name'] =  $low['firstName'];
					$mustobservedata['age'] = $low['age'];
				    $mustobservedata['gender'] = $low['gender'];
					$date = date('Y-m-01',strtotime(date('Y-m-d')));
					$mustobservedata['deviceuserd'] = "";
				    $mustobservedata['lastreview'] = date('M d,Y',strtotime($date));
					$statusarray[0]['key'] = 'Avg Glucose';
					$avg = Glucose::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					if(empty($avg))
					{
						$statusarray[0]['value'] = 0;
					}
					else
					{
						$statusarray[0]['value'] = 1;
					}
					$statusarray[0]['status'] = 1;
					$mealavg = Usermeals::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					$statusarray[1]['key'] = 'Meal Log';
					if(empty($mealavg))
					{
						$statusarray[1]['value'] = 0;
					}
					else
					{
						$statusarray[1]['value'] = 1;
					}
					$statusarray[1]['status'] = 1;
					$statusarray[2]['key'] = 'Blood Pressure';
					$bpavg = Bp::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
					if(empty($bpavg))
					{
						$statusarray[2]['value'] = 0;
					}
					else
					{
						$statusarray[2]['value'] = 1;
					}
					$statusarray[2]['status'] = 0;
					$mustobservedata['status'] = $statusarray;
					$mustobservearray[0] = $mustobservedata;			
				}
				$lowperform= Glucose::find()->where(['access_token'=>'seUBkt9uu_zopYFDAOuAYnWGBOvDKlaA2tTryijm'])->one();
				if(!empty($lowperform))
				{
					$low = Userprofile::find()->where(['userId'=>"31"])->one();
					$statusarray = [];
					$lowperformcount = 1;
					$lowperformdata['patId'] =  $low['userId'];
					$lowperformdata['name'] =  $low['firstName'];
					$lowperformdata['age'] = $low['age'];
				    $lowperformdata['gender'] = $low['gender'];
					$date = date('Y-m-01',strtotime(date('Y-m-d')));
					$lowperformdata['deviceuserd'] = "";
				    $lowperformdata['lastreview'] = date('M d,Y',strtotime($date));
					$statusarray[0]['key'] = 'Avg Glucose';
					$statusarray[0]['value'] = 0;
					$statusarray[0]['status'] = 1;
					$statusarray[1]['key'] = 'Meal Log';
					$statusarray[1]['value'] = 0;
					$statusarray[1]['status'] = 0;
					$statusarray[2]['key'] = 'Blood Pressure';
					$statusarray[2]['value'] = 0;
					$statusarray[2]['status'] = 0;
					$lowperformdata['status'] = $statusarray;
					$lowperformarray[0] = $lowperformdata;					
				}
			}
			$data[0]['title'] = 'Total Patients';
			$data[0]['value'] = $totalpatients;
			$data[1]['title'] = 'Adherence';
			$data[1]['value'] = $topperformcount;
			$data[2]['title'] = 'Observation Required';
			$data[2]['value'] = $mustobservecount;
			$data[3]['title'] = 'Urgent Attention';
			$data[3]['value'] = $lowperformcount;
			$webinardata = [];
			$futurewebinars = [];
			//print_r($user->roleName);exit;
			if($user->roleName == "Doctor")
			{
				$doctor = Doctors::find()->where(['userId'=>$user->id])->one()->doctorId;
				$webinars = Webinars::find()->where(['doctorId'=>$doctor])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->count();
				$Webinarenrolls = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->count();
				$joined = 0;
				$converted = 0;			
				$webinardata[0]['title'] = 'Total Webinars';
				$webinardata[0]['value'] = $webinars;
				$webinardata[1]['title'] = 'Joined';
				$webinardata[1]['value'] = $joined;
				$webinardata[2]['title'] = 'Converted';
				$webinardata[2]['value'] = $converted;
				$futurewebinarscount = Webinars::find()->where(['doctorId'=>$doctor])->andwhere(['>=','webinars.createdDate',date('Y-m-d')])->count();
				$futurewebinars[0]['title'] = 'Total Webinars';
				$futurewebinars[0]['value'] = $futurewebinarscount;
				$futurewebinars[1]['title'] = 'Enrolled';
				$futurewebinars[1]['value'] = $Webinarenrolls;
			}
			return ['status' => true, 'message' => 'Success','data'=>$data,'Futurewebinars'=>$futurewebinars,'totalpatients'=>$totalpatientsarray,'mustobservearray'=>$mustobservearray,'topperformarray'=>$topperformarray,'lowperformarray'=>$lowperformarray,'webinardata'=>$webinardata];
	   }		
	}
    public static function actionBpchart()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$dates = date('Y-m-d');
			$averageglucose = (Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('SystolicValue')).'/'.(Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('DiastolicValue'));
			
			if($get['type'] == 'day')
			{
				$morningsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				$morningdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				
				$data[0]['name'] = "Break Fast";
				$data[0]['avgbp'] = $morningsysavg.' / '. $morningdiaavg;
				
				$afternoonsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				$afternoondiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
				$data[1]['name'] = "Lunch";
				$data[1]['avgbp'] = $afternoonsysavg.' / '. $afternoondiaavg;
				
				$dinnersysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				$dinnerdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				$data[2]['name'] = "Dinner";
				$data[2]['avgbp'] = $dinnersysavg.' / '. $dinnerdiaavg;
								
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$d = date("l");
				$start_week = strtotime("last ".$d." midnight",$previous_week);				
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d");
				$dates = $start_week.' To '.$end_week;
				
				
				$i= 0;
				$averageglucose = ceil(Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('SystolicValue')).'/'.ceil(Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('DiastolicValue'));
			    $noonfasting = number_format(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
			    $fasting = number_format(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningsysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('DiastolicValue');
				      
				
						$data[$i]['avgbp'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
						
						$i++;
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($start_week)));
				}
				
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');				
				$firstweekstart = date('Y').'-'.$yourMonth.'-'.date('d');
				$firstweekend = date('Y-m-d',strtotime("+7 day", strtotime($firstweekstart)));
				$dates = $firstweekstart.' To '.date('Y-m-d');
				$currentYear = date('Y');
				$currentMonth = date('m');
				$averageglucose = ceil(Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('SystolicValue')).'/'.ceil(Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('DiastolicValue'));
			    $noonfasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
			    $fasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				
				for($i=0;$i<4;$i++)
				{
					if($i == 0)
					{
						$name = "First Week";
						$start_week = $firstweekstart;
						$end_week = $firstweekend;
					}
					if($i == 1)
					{
						$name = "Second Week";
					}
					if($i == 2)
					{
						$name = "Third Week";
					}
					if($i == 3)
					{
						$name = "Current Week";
					}
					if($i>0)
					{
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($end_week)));
						$end_week = date('Y-m-d',strtotime("+7 day", strtotime($start_week)));
					}
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
					$data[$i]['name'] = $name;					
					$morningsysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('SystolicValue');
				    $morningdiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('DiastolicValue');
				    $data[$i]['avgbp'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);			
									
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				//$records = Glucose::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->distinct()->all();
				//print_r($records);exit;
				$currentYear = date('Y');
				$averageglucose = ceil(Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('SystolicValue')).'/'.ceil(Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('DiastolicValue'));
			    $noonfasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
			    $fasting = number_format(Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				
				for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						
						$morningsysavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('DiastolicValue');
				        
						$data[$i]['avgbp'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
						//$data[$i]['lunch'] = ceil($afternoonsysavg).' / '.ceil($afternoondiaavg);
						//$data[$i]['dinner'] = ceil($dinnersysavg).' / '.ceil($dinnerdiaavg);	
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionCalender()	{
		$data = array();
		date_default_timezone_set("Asia/Calcutta");
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			//$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$patients =  Userprofile::find()->all();
			if(!empty($patients))
			{
						foreach($patients as $key=>$value)
						{																
							$data[$key]['title'] = $value['firstName'];
							$date = date('Y-m-d');
							if($key ==0)
							{
								//$date = date('Y-m-d',strtotime("-1 days"));
								$date = str_replace('IST','T',date('Y-m-d'.'T10:00:00',strtotime($date)));
							}
							if($key ==1)
							{
								$date = str_replace('IST','T',date('Y-m-d'.'T12:00:00',strtotime($date)));
							}
							if($key ==2)
							{
								$date = str_replace('IST','T',date('Y-m-d'.'T13:00:00',strtotime($date)));
							}
							//$date = date('Y-m-d');
							$data[$key]['start'] = $date;
							$data[$key]['end'] = $date;
							$data[$key]['age'] = $value['age'];
							$data[$key]['gender'] = $value['gender'];
							$data[$key]['name'] = $value['firstName'];
							$data[$key]['patId'] = $value['userId'];
							$date = date('Y-m-01',strtotime(date('Y-m-d')));
							$data[$key]['deviceuserd'] = "";
							$data[$key]['lastreview'] = date('M d,Y',strtotime($date));
							$data[$key]['remarks'] = "Remarks";
							$data[$key]['Status'] = $value['Status'];
						}
			}			
			return ['status' => true, 'message' => 'Success','data'=>$data];
	   }
	}
	public static function actionWeightchart()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$dates = date('Y-m-d');
			$bmiarray = [];
			$height = Bmivalues::find()->where(['access_token'=>$access_token])->one()->height;
			if($get['type'] == 'day')
			{
				$weightavg = Bmivalues::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->average('weight');
				$data[0]['name'] = date('Y-m-d');
				$data[0]['weight'] = number_format($weightavg,1);
				$bmiavg = Bmivalues::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->average('BMI');
				$bmiarray[0]['name'] = date('Y-m-d');
				$bmiarray[0]['BMI'] = number_format($bmiavg,1);
				
				
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$d = date("l");
				$start_week = strtotime("last ".$d." midnight",$previous_week);				
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d");
				$dates = $start_week.' To '.$end_week;
				$i= 0;
				$averageglucose = 0;
				$divide = 0;
				
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$weightavg = Bmivalues::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('weight');
						$data[$i]['weight'] = number_format($weightavg,1);
						$bmiavg = Bmivalues::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('BMI');
						$bmiarray[$i]['name'] = date("l",strtotime($start_week));
				        $bmiarray[$i]['BMI'] = number_format($bmiavg,1);
						$i++;
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($start_week)));
				}			 
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');				
				$firstweekstart = date('Y').'-'.$yourMonth.'-'.date('d');
				$firstweekend = date('Y-m-d',strtotime("+7 day", strtotime($firstweekstart)));
				$dates = $firstweekstart.' To '.date('Y-m-d');
				$currentYear = date('Y');
				$currentMonth = date('m');
				
				for($i=0;$i<4;$i++)
				{
					if($i == 0)
					{
						$name = "First Week";
						$start_week = $firstweekstart;
						$end_week = $firstweekend;
					}
					if($i == 1)
					{
						$name = "Second Week";
					}
					if($i == 2)
					{
						$name = "Third Week";
					}
					if($i == 3)
					{
						$name = "Current Week";
					}
					if($i>0)
					{
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($end_week)));
						$end_week = date('Y-m-d',strtotime("+7 day", strtotime($start_week)));
					}
					$data[$i]['name'] = $name;
					$weightavg= Bmivalues::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('weight');
					$data[$i]['weight'] = number_format($weightavg,1);	
                    $bmiavg= Bmivalues::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('BMI');
					$bmiarray[$i]['name'] = $name;
					$bmiarray[$i]['BMI'] = number_format($bmiavg,1);					
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				$currentYear = date('Y');
				for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$weightavg = Bmivalues::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('weight');
						$data[$i]['weight'] = number_format($weightavg,1);
						$data[$i]['Spo2'] = "100";
						$bmiavg = Bmivalues::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('BMI');
						$bmiarray[$i]['name'] = date("M",strtotime($date));
						$bmiarray[$i]['BMI'] = number_format($bmiavg,1);
						$bmiarray[$i]['Spo2'] = "100";
						
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data,'height'=>$height,'spo2'=>100,'bmiarray'=>$bmiarray];
	   }
	}
	public static function actionBmichart()	{
		$data = array();
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
                return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
			$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
			$dates = date('Y-m-d');
			if($get['type'] == 'day')
			{
				$bmiavg = Bmivalues::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->average('BMI');
				$data[0]['name'] = date('Y-m-d');
				$data[0]['BMI'] = number_format($bmiavg,1);
				
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$d = date("l");
				$start_week = strtotime("last ".$d." midnight",$previous_week);				
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d");
				$dates = $start_week.' To '.$end_week;
				$i= 0;
				$averageglucose = 0;
				$divide = 0;
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$bmiavg = Bmivalues::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('BMI');
						$data[$i]['BMI'] = number_format($bmiavg,1);
						$i++;
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($start_week)));
				}			 
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');				
				$firstweekstart = date('Y').'-'.$yourMonth.'-'.date('d');
				$firstweekend = date('Y-m-d',strtotime("+7 day", strtotime($firstweekstart)));
				$dates = $firstweekstart.' To '.date('Y-m-d');
				$currentYear = date('Y');
				$currentMonth = date('m');
				for($i=0;$i<4;$i++)
				{
					if($i == 0)
					{
						$name = "First Week";
						$start_week = $firstweekstart;
						$end_week = $firstweekend;
					}
					if($i == 1)
					{
						$name = "Second Week";
					}
					if($i == 2)
					{
						$name = "Third Week";
					}
					if($i == 3)
					{
						$name = "Current Week";
					}
					if($i>0)
					{
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($end_week)));
						$end_week = date('Y-m-d',strtotime("+7 day", strtotime($start_week)));
					}
					$data[$i]['name'] = $name;
					$bmiavg= Bmivalues::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('BMI');
					$data[$i]['BMI'] = number_format($bmiavg,1);					
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				$currentYear = date('Y');
				for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$bmiavg = Bmivalues::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('BMI');
						$data[$i]['BMI'] = number_format($bmiavg,1);
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data];
	   }
	}
	public function actionBookings()    {        
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->get();		
		$Authorization = Yii::$app->request->headers->get('Authorization');	
		if (empty($Authorization))
		{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
		}
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
		if(empty($user))
        {
                return ['status' => false, 'message' => 'Invalid Access token'];
        }
        else
        {
				$data = [];
				$futuredata = [];
                $access_token = str_replace('Bearer ','',$Authorization);
				if($user->roleId == 3)
				{
					$doctor = Doctors::find()->where(['userId'=>$user->id])->one();
					$model = Slotbooking::find()->where(['doctorId'=>$doctor->doctorId])->andwhere(['!=','status','Cancel'])->all();
					$webinarscount = Webinars::find()->where(['doctorId'=>$user->id])->andwhere(['<','webinars.createdDate',date('Y-m-d')])->andwhere(['Status'=>"Active"])->all();
					$webinardata = [];
					foreach($webinarscount as $wkey=>$wvalue)
					{
						$webinardata[$wkey]['bookingId'] = $wvalue->webnarId;  
						$webinardata[$wkey]['tile'] = $wvalue->webinarName;  
						$time = explode(' - ',$wvalue->time);
						//print_r($wvalue->PublishDate);exit;
						$webinardata[$wkey]['start'] = $wvalue->PublishDate.'T'.date('H:i:s',strtotime($time[0]));
						$webinardata[$wkey]['end'] = $wvalue->PublishDate.'T'.date('H:i:s',strtotime($time[1]));
						$webinardata[$wkey]['link'] = $wvalue->meetingUrl;
						$webinardata[$wkey]['status'] = 'Webinar';
					}
					//print_r($webinardata);exit;
					$futurewebinarscount = Webinars::find()->where(['doctorId'=>$user->id])->andwhere(['>=','webinars.createdDate',date('Y-m-d')])->andwhere(['Status'=>"Active"])->all();
					$futuredata = [];
					foreach($futurewebinarscount as $fkey=>$fvalue)
					{
						$futuredata[$fkey]['bookingId'] = $fvalue->webnarId;
						$futuredata[$fkey]['tile'] = $fvalue->webinarName;  
						$time = explode(' - ',$fvalue->time);
						//print_r($time[0]);exit;
						$futuredata[$fkey]['start'] = $fvalue->PublishDate.'T'.date('H:i:s',strtotime($time[0]));
						$futuredata[$fkey]['end'] = $fvalue->PublishDate.'T'.date('H:i:s',strtotime($time[1]));
						$futuredata[$fkey]['link'] = $fvalue->meetingUrl;
						$futuredata[$fkey]['status'] = 'Webinar';
					}
					$futuredata = array_merge($webinardata,$futuredata);
				}
				elseif($user->roleId == 5)
				{
					$doctor = Dietician::find()->where(['userId'=>$user->id])->one();
					$model = Dieticianslotbooking::find()->where(['dieticianId'=>$doctor->dieticianId])->all();
					$webinardata = [];
					$futuredata = [];
					$futuredata = array_merge($webinardata,$futuredata);
				}				
				foreach($model as $key=>$value)
				{
					$usernew = User::find()->where(['access_token'=>$value->access_token])->one();
					$profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();
					$data[$key]['tile'] = $profile->firstName; 
					$data[$key]['access_token'] = $profile->access_token; 
					$data[$key]['start'] = $value->slotDate.'T'.date('H:i:s',strtotime($value->slotTime));
					$data[$key]['end'] = $value->slotDate.'T'.date('H:i:s',strtotime($value->slotTime));
					$data[$key]['status'] = $value->status;
					$data[$key]['slotId'] = $value->slotId;
					$data[$key]['bookingId'] = $value->bookingId;
					$data[$key]['age'] = $profile['age'];
					$data[$key]['mobilenumber'] = $usernew->username;
					$data[$key]['gender'] = $profile['gender'];
					$data[$key]['name'] = $profile['firstName'];
					$data[$key]['patId'] = $profile['userId'];
					$date = date('Y-m-01',strtotime(date('Y-m-d')));
					$data[$key]['deviceuserd'] = "";
					$data[$key]['lastreview'] = date('M d,Y',strtotime($date));
				}
				$data = array_merge($data,$futuredata);
                return ['status' => true, 'message' => 'Success','model'=>$data];               
        }
	}  
	public function actionSlotreshedule()    {
        
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
		
        if (Yii::$app->request->post()) 
        {
			$Authorization = Yii::$app->request->headers->get('Authorization');	
			
			if (empty($Authorization))
			{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
			}
			
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
			//print_r($user);exit;
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
              	$model = Slotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$newmodel = Slotbooking::find()->where(['slotDate'=>$post['slotDate'],'slotTime'=>$post['slotTime'],'doctorId'=>$model->doctorId,'Status'=>'Booked'])->one();
				if(empty($newmodel))
				{
					$model->slotId =  $post['slotId']; 
					$model->slotTime =  $post['slotTime']; 
					$model->slotDate = $post['slotDate'];
					$model->status = "Reshedule";
					$model->resheduleremarks = $post['remarks'];
					$model->updatedDate = date('Y-m-d'); ;
					$model->save();	
				}
				else
				{
					return ['status' => false, 'message' => 'Already Slot Is booked Please check another slot']; 
				}
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->bookingId];              
                
            }
        }
        
    }
	public function actionSlotcancel()    {
        
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
		
        if (Yii::$app->request->post()) 
        {
			$Authorization = Yii::$app->request->headers->get('Authorization');	
			
			if (empty($Authorization))
			{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
			}
			
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
			//print_r($user);exit;
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Slotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$model->status = "Cancel";
				//$model->access_token =  $access_token;
				$model->cancelremarks = $post['remarks'];
				$model->updatedDate = date('Y-m-d'); ;
				$model->save();			
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->bookingId];              
                
            }
        }
        
    }
	public function actionDieticianBookings()    {        
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->get();		
		$Authorization = Yii::$app->request->headers->get('Authorization');	
		if (empty($Authorization))
		{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
		}
		$user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
		if(empty($user))
        {
                return ['status' => false, 'message' => 'Invalid Access token'];
        }
        else
        {
				$data = [];
				$futuredata = [];
                $access_token = str_replace('Bearer ','',$Authorization);
				$doctor = Dietician::find()->where(['userId'=>$user->id])->one();
				$model = Dieticianslotbooking::find()->where(['dieticianId'=>$doctor->dieticianId])->andwhere(['!=','status','Cancel'])->all();
				foreach($model as $key=>$value)
				{
					$usernew = User::find()->where(['access_token'=>$value->access_token])->one();
					$profile = Userprofile::find()->where(['userId'=>$usernew->id])->one();
					$data[$key]['tile'] = $profile->firstName; 
					$data[$key]['start'] = $value->slotDate.'T'.date('H:i:s',strtotime($value->slotTime));
					$data[$key]['end'] = $value->slotDate.'T'.date('H:i:s',strtotime($value->slotTime));
					$data[$key]['status'] = $value->status;
					$data[$key]['slotId'] = $value->slotId;
					$data[$key]['bookingId'] = $value->bookingId;
					$data[$key]['age'] = $profile['age'];
					$data[$key]['gender'] = $profile['gender'];
					$data[$key]['name'] = $profile['firstName'];
					$data[$key]['patId'] = $profile['userId'];
					$date = date('Y-m-01',strtotime(date('Y-m-d')));
					$data[$key]['deviceuserd'] = "";
					$data[$key]['lastreview'] = date('M d,Y',strtotime($date));
				}
				$data = array_merge($data,$futuredata);
                return ['status' => true, 'message' => 'Success','model'=>$data];               
        }
	}  
	public function actionDieticianSlotreshedule()    {
        
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
		
        if (Yii::$app->request->post()) 
        {
			$Authorization = Yii::$app->request->headers->get('Authorization');	
			
			if (empty($Authorization))
			{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
			}
			
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
			//print_r($user);exit;
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
              //  $access_token = str_replace('Bearer ','',$Authorization);
				$model = Dieticianslotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$newmodel = Dieticianslotbooking::find()->where(['slotDate'=>$post['slotDate'],'slotTime'=>$post['slotTime'],'dieticianId'=>$model->dieticianId,'Status'=>'Booked'])->one();
				if(empty($newmodel))
				{
					$model->slotId =  $post['slotId']; 
					$model->slotTime =  $post['slotTime']; 
					$model->slotDate = $post['slotDate'];
					$model->status = "Reshedule";
					$model->resheduleremarks = $post['remarks'];
					$model->updatedDate = date('Y-m-d'); ;
					$model->save();	
				}
				else
				{
					return ['status' => false, 'message' => 'Already Slot Is booked Please check another slot']; 
				}
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->bookingId];                          }
        }        
    }	
	public function actionDieticianSlotcancel()    {        
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();		
        if (Yii::$app->request->post()) 
        {
			$Authorization = Yii::$app->request->headers->get('Authorization');			
			if (empty($Authorization))
			{
				return ['status' => false, 'message' => 'Please Add Authorization Token '];
			}			
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
			//print_r($user);exit;
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Dieticianslotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$model->status = "Cancel";
				$model->cancelremarks = $post['remarks'];
				$model->updatedDate = date('Y-m-d'); ;
				$model->save();			
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->bookingId];               
            }
        }        
    }	
	public static function actionSlots()    {
		date_default_timezone_set("Asia/Calcutta");
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();        
        $get = Yii::$app->request->get();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
        else
        {
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
				if($user->roleId == 3)
				{
					$usernew = Doctors::find()->where(['userId'=>$user->id])->one()->doctorId;
					$slots = Slots::find()->where(['doctorId'=>$usernew,'slotDate'=>$_GET['date']])->all();
					$model = [];
					if($slots != [])
					{
						
							foreach($slots as $key=>$value)
							{
								
								if($_GET['date'] == date('Y-m-d'))
								{
									if(date('H:i') < date('H:i',strtotime($value['slotTime'])))
									{
										$slotbooking = Slotbooking::find()->where(['slotId'=>$value['slotId']])->andwhere(['!=','Status','Cancel'])->one();
										if(empty($slotbooking))
										{
											$model[] = $value;
										}
									}
								}
								else
								{
									$slotbooking = Slotbooking::find()->where(['slotId'=>$value['slotId']])->andwhere(['!=','Status','Cancel'])->one();
									if(empty($slotbooking))
									{
											$model[] = $value;
									}
								}
							}
					}					
				}	
			    elseif($user->roleId == 5)
				{
					$usernew = Dietician::find()->where(['userId'=>$user->id])->one()->dieticianId;
					$slots = Dslots::find()->where(['dieticianId'=>$usernew,'slotDate'=>$_GET['date']])->all();	
					$model = [];
				if($slots != [])
				{
					
						foreach($slots as $key=>$value)
						{
							
							if(date('Y-m-d',strtotime($_GET['date'])) == date('Y-m-d'))
							{
								if(date('H:i') < date('H:i',strtotime($value['slotTime'])))
								{
									$slotbooking = Dieticianslotbooking::find()->where(['slotId'=>$value['slotId']])->andwhere(['!=','Status','Cancel'])->one();
									if(empty($slotbooking))
									{
										$model[] = $value;
									}
								}
						    }
							else
							{
								$slotbooking = Dieticianslotbooking::find()->where(['slotId'=>$value['slotId']])->andwhere(['!=','Status','Cancel'])->one();
								if(empty($slotbooking))
								{
										$model[] = $value;
								}
							}
					    }
					}               
				}
                return ['status' => true, 'message' => 'Slots', 'data' => $model];             
            }
        } 
    }   
	public static function actionSlotview()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();        
        $get = Yii::$app->request->get();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
        else
        {
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
				
				$model = Slotbooking::find()->where(['bookingid'=>$_GET['bookingid']])->one();			
                return ['status' => true, 'message' => 'Slots', 'data' => $model];             
            }
        } 
    }
	public static function actionDoctorSlotstatus()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();        
        $get = Yii::$app->request->get();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
        else
        {
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
				
				$model = Slotbooking::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				if(!empty($model))
				{
					$model->status = 'Completed';
					$model->save();
				}
                return ['status' => true, 'message' => 'Success'];             
            }
        } 
    }
	public static function actionDieticianSlotstatus()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();        
        $get = Yii::$app->request->get();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
        else
        {
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {				
				$model = Dieticianslotbooking::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				if(!empty($model))
				{
					$model->status = 'Completed';
					$model->save();
				}
                return ['status' => true, 'message' => 'Success'];             
            }
        } 
    }
}
?>