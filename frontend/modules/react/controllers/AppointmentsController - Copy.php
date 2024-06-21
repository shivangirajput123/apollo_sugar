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
use backend\modules\common\models\Portions;
class AppointmentsController extends Controller
{
    public static function actionIndex()
    {
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
							$data[$key]['lastreview'] = date('M d,Y',strtotime($value['updatedDate']));
							$data[$key]['remarks'] = "Remarks";
							$data[$key]['Status'] = $value['Status'];
						}
				}
				$date = date('Y-m-d');
				/*$totalappointments = Appointments::find()->where(['doctorId'=>$get['doctorId'],'createdDate'=>$date])->count();
				$completedappointments = Appointments::find()->where(['doctorId'=>$get['doctorId'],'Status'=>'Completed'])->count();
				$mustconsultappointments = Appointments::find()->where(['doctorId'=>$get['doctorId'],'Status'=>'Must Consult'])->count();
				$pendingappointments = Appointments::find()->where(['doctorId'=>$get['doctorId'],'Status'=>'Pending'])->count();*/
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
    public static function actionView()
    {
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
				$data['lastreview'] = date('M d,Y',strtotime($value->updatedDate));
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
    
	
	public static function actionRemarks()
    {
        $data = array();        
        $get = Yii::$app->request->post();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$value = Appointments::find()->where(['apId'=>$get['apId']])->one();
		if(!empty($value))
		{
			$value->remarks = $get['remarks'];
			$value->save();
			return ['status' => true, 'message' => 'Success','data' => [$value]];
		}
		else
		{
			return ['status' => false, 'message' => 'Fail'];
		}
	}
	
	public static function actionDietplans()
	{
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
					if(!isset($_GET['fromdate']))
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
						//print_r($start_week);
						//print_r($end_week);exit;
					}
					
					$plans = Dietplans::find()->where(['userId'=>$get['userId'],'createdDate'=>$start,'updatedDate'=>$end])->all();
					//print_r($plans);exit;
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
					$previous_weekplans = Dietplans::find()->where(['userId'=>$get['userId'],'createdDate'=>$start_week,'updatedDate'=>$end_week])->all();
					$previous = [];
					if(!empty($previous_weekplans))
					{
						foreach($previous_weekplans as $x=>$y)
						{
							$previous[$x]['time'] = $y->time;
							$previous[$x]['type'] = $y->mealtype;
							$items = Dietplandetails::find()->where(['planId'=>$y->planId])->all();
							/*foreach($items as $k=>$v)
							{
								$itemlist[$k] = $v['itemName'].' - '.$v['quantity'].'|'.$v['calories'];
							}*/
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
	
	public static function actionExcerciseplans()
	{
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
	
	public static function actionChartdetails()
	{
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
				//$records = Appointmentrecords::find()->select('createdDate')->where(['>', 'createdDate', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])->distinct()->all();
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
	
	public static function actionDiettrackchart()
	{
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
				//$records = Appointmentrecords::find()->select('createdDate')->where(['>', 'createdDate', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])->distinct()->all();
				$records = Diettrack::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['patId'=>$get['patId']])->distinct()->all();
				//print_r($records);exit;
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
				//print_r($records);exit;
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
	
	public static function actionGlucosechart()
	{
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
				$noonfasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
				$fasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
					
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
				//$records = Glucose::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->distinct()->all();
				$i= 0;
				$averageglucose = ceil(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
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
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
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
				//$records = Glucose::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->distinct()->all();
				//print_r($records);exit;
				$currentYear = date('Y');
				$averageglucose = ceil(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
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
	public static function actionMealchart()
	{
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
			$averageglucose = ceil(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('glucosevalue'));
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
				
				$averageglucose = ceil(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
				$i= 0;
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningavg = Usermeals::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
						$afternoonavg = Usermeals::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
					    $dinneravg = Usermeals::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
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
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
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
				//$records = Glucose::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->distinct()->all();
				//print_r($records);exit;
				$currentYear = date('Y');
				$averageglucose = ceil(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
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
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'nonfasting'=>$noonfasting,'fasting'=>$fasting,'dates'=>$dates,'data'=>$data];
	   }
	}
	public function actionUserplandetails()
	{
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
								$consultations[$x]['day'] = $v['day'];
								$consultations[$x]['date'] = date('d-M-Y',strtotime($v['date']));
								$consultations[$x]['text'] = $v['text'];
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
	public function actionMeallog()
	{
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
			
			return ['status' => true, 'message' => 'Success','data'=>$data];
	   }		
	}
	
	public function actionUserMealTrack()
	{
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
	
	
	public function actionDashboard()
	{
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
			//$access_token = User::find()->where(['id' => $get['patId']])->one()->access_token;
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
					//$lowperformcount = $lowperformcount + 1;
					$low = Userprofile::find()->where(['userId'=>"30"])->one();
					$mustobservedata['patId'] =  $low['userId'];
					$mustobservedata['name'] =  $low['firstName'];
					$mustobservedata['age'] = $low['age'];
				    $mustobservedata['gender'] = $low['gender'];
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
			$data[1]['title'] = 'Top Performers';
			$data[1]['value'] = $topperformcount;
			$data[2]['title'] = 'Must Observe';
			$data[2]['value'] = $mustobservecount;
			$data[3]['title'] = 'Low Performers';
			$data[3]['value'] = $lowperformcount;
			$webinardata = [];
			if($user->roleName == "doctor")
			{
			$doctor = Doctors::find()->where(['userId'=>$user->id])->one()->doctorId;
			$webinars = Webinars::find()->where(['doctorId'=>$doctor])->count();
			$Webinarenrolls = Webinarenrolls::find()->innerjoin('webinars','webinars.webnarId=webinarenrolls.webinarId')->where(['doctorId'=>$user->id])->count();
			$joined = 0;
			$converted = 0;
			
			
			$webinardata[0]['title'] = 'Total Webinars';
			$webinardata[0]['value'] = $webinars;
			$webinardata[1]['title'] = 'Enrolled';
			$webinardata[1]['value'] = $Webinarenrolls;
			$webinardata[2]['title'] = 'Joined';
			$webinardata[2]['value'] = $joined;
			$webinardata[3]['title'] = 'Converted';
			$webinardata[3]['value'] = $converted;
			}
			return ['status' => true, 'message' => 'Success','data'=>$data,'totalpatients'=>$totalpatientsarray,'mustobservearray'=>$mustobservearray,'topperformarray'=>$topperformarray,'lowperformarray'=>$lowperformarray,'webinardata'=>$webinardata];
	   }		
	}
	public static function actionBpchartbkp()
	{
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
			$averageglucose = ceil(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('glucosevalue'));
			$noonfasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			$fasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
			
			if($get['type'] == 'day')
			{
				$morningsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				$morningdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				
				$data[0]['name'] = "Break Fast";
				$data[0]['breakfast'] = $morningsysavg;
				$data[0]['diavalue'] = $morningdiaavg;
				$afternoonsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				$afternoondiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
				$data[1]['name'] = "Lunch";
				$data[1]['lunch'] = $afternoonsysavg;
				$data[1]['diavalue'] = $afternoondiaavg;
				$dinnersysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				$dinnerdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				$data[2]['name'] = "Dinner";
				$data[2]['dinner'] = $dinnersysavg;
				$data[2]['diavalue'] = $dinnerdiaavg;			
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
			    $noonfasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningsysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				        
						$afternoonsysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				        $afternoondiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
						
						$dinnersysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				        $dinnerdiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				
						$data[$i]['breakfast'] = ceil($morningsysavg).'/'.ceil($morningdiaavg);
						$data[$i]['lunch'] = ceil($afternoonsysavg).'/'.ceil($afternoondiaavg);
						$data[$i]['dinner'] = ceil($dinnersysavg).'/'.ceil($dinnerdiaavg);
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
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
					$data[$i]['name'] = $name;
					
					
						$morningsysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				        
						$afternoonsysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				        $afternoondiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
						
						$dinnersysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				        $dinnerdiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				
						$data[$i]['breakfast'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
						$data[$i]['lunch'] = ceil($afternoonsysavg).' / '.ceil($afternoondiaavg);
						$data[$i]['dinner'] = ceil($dinnersysavg).' / '.ceil($dinnerdiaavg);
									
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				//$records = Glucose::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->distinct()->all();
				//print_r($records);exit;
				$currentYear = date('Y');
				$averageglucose = ceil(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
				for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						
						$morningsysavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				        
						$afternoonsysavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				        $afternoondiaavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
						
						$dinnersysavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				        $dinnerdiaavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				
						$data[$i]['breakfast'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
						$data[$i]['lunch'] = ceil($afternoonsysavg).' / '.ceil($afternoondiaavg);
						$data[$i]['dinner'] = ceil($dinnersysavg).' / '.ceil($dinnerdiaavg);	
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'nonfasting'=>$noonfasting,'fasting'=>$fasting,'dates'=>$dates,'data'=>$data];
	   }
	}
	
	public static function actionBpchart()
	{
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
			$averageglucose = ceil(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('glucosevalue'));
			$noonfasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			$fasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
			
			if($get['type'] == 'day')
			{
				$morningsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				$morningdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				
				$data[0]['name'] = "Break Fast";
				$data[0]['breakfast'] = $morningsysavg;
				$data[0]['diavalue'] = $morningdiaavg;
				$afternoonsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				$afternoondiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
				$data[1]['name'] = "Lunch";
				$data[1]['lunch'] = $afternoonsysavg;
				$data[1]['diavalue'] = $afternoondiaavg;
				$dinnersysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				$dinnerdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				$data[2]['name'] = "Dinner";
				$data[2]['dinner'] = $dinnersysavg;
				$data[2]['diavalue'] = $dinnerdiaavg;			
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
			    $noonfasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
				while($start_week <= $end_week)
				{
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningsysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				        
						$afternoonsysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				        $afternoondiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
						
						$dinnersysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				        $dinnerdiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				
						$data[$i]['breakfast'] = ceil($morningsysavg).'/'.ceil($morningdiaavg);
						$data[$i]['lunch'] = ceil($afternoonsysavg).'/'.ceil($afternoondiaavg);
						$data[$i]['dinner'] = ceil($dinnersysavg).'/'.ceil($dinnerdiaavg);
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
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
					$data[$i]['name'] = $name;
					
					
						$morningsysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				        
						$afternoonsysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				        $afternoondiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
						
						$dinnersysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				        $dinnerdiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				
						$data[$i]['breakfast'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
						$data[$i]['lunch'] = ceil($afternoonsysavg).' / '.ceil($afternoondiaavg);
						$data[$i]['dinner'] = ceil($dinnersysavg).' / '.ceil($dinnerdiaavg);
									
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				//$records = Glucose::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->distinct()->all();
				//print_r($records);exit;
				$currentYear = date('Y');
				$averageglucose = ceil(Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
			    $noonfasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			    $fasting = Glucose::find()->where(" Year( createdDate) = $currentYear ")->where(['access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
				
				for($i=0;$i<12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						
						$morningsysavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('DiastolicValue');
				        
						$data[$i]['breakfast'] = ($morningsysavg).' / '.($morningdiaavg);
						//$data[$i]['lunch'] = ceil($afternoonsysavg).' / '.ceil($afternoondiaavg);
						//$data[$i]['dinner'] = ceil($dinnersysavg).' / '.ceil($dinnerdiaavg);	
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'nonfasting'=>$noonfasting,'fasting'=>$fasting,'dates'=>$dates,'data'=>$data];
	   }
	}
	
	
}
?>