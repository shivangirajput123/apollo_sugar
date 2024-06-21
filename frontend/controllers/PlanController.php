<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use backend\modules\common\models\Portions;
use common\models\User;
use backend\modules\plans\models\Dietplans;
use backend\modules\plans\models\Dietplandetails;
use backend\modules\common\models\Fooditemdetails;
use backend\modules\plans\models\Excerciseplans;
use backend\modules\plans\models\Excerciseplandetails;
use backend\modules\common\models\Categories;
use backend\modules\common\models\Excercise;
use backend\modules\common\models\Labtests;
use backend\modules\common\models\Durations;
use backend\modules\common\models\Usagemaster;
use backend\modules\common\models\Medicinemaster;
use backend\modules\plans\models\Userexcercisetrack;
use frontend\models\Remarks;
use frontend\models\Patientallergies;
use frontend\models\Patientsymptoms;
use backend\modules\users\models\Prescription;
use backend\modules\users\models\Medicines;
use backend\modules\users\models\DoctorTests;
use backend\modules\users\models\Doctors;
use frontend\models\Userprofile;
use backend\modules\users\models\Bmivalues;
use frontend\models\Glucose;
use backend\modules\users\models\Usermeals;
use backend\modules\users\models\Slots;
use backend\modules\common\models\Bp;
use backend\modules\common\models\Fooditems;
use backend\modules\franchies\models\Franchies;
use frontend\models\Userplans;
use backend\modules\packages\models\Plans;
/**
 * Default controller for the `quiz` module
 */
class PlanController extends Controller
{
	
	public static function actionValidateaccess_token()	{
	  $user = User::find()
            ->where(['access_token' => $_GET['access_token']])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
       if(empty($user))
       {
           return ['status' => false, 'message' => 'Invalid Access token'];
       }
	   else
	   {
		  return ['status' => true, 'message' => 'Token Validated','id'=>$user->id];
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
			$access_token = str_replace('Bearer ','',$Authorization);
			$dates = date('Y-m-d');
			if($get['type'] == 'day')
			{
				$averageglucose = ceil(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('glucosevalue'));
				$noonfasting = number_format(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue'),2);
				$fasting = number_format(Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue'),2);
				$morningavg = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealid'=>1 ])->average('glucosevalue');
				$data[0]['x'] = 1;
				$data[0]['name'] = "Break Fast";
				$data[0]['breakfast'] = ceil($morningavg);
				$afternoonavg = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealid'=>2 ])->average('glucosevalue');
				$data[1]['x'] = 2;
				$data[1]['name'] = "Lunch";
				$data[1]['breakfast'] = ceil($afternoonavg);
				$dinneravg = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealid'=>3 ])->average('glucosevalue');
				$data[2]['x'] = 3;
				$data[2]['name'] = "Dinner";
				$data[2]['breakfast'] = ceil($dinneravg);
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
					
						if(date("l",strtotime($start_week)) == 'Sunday')
						{
							$data[$i]['x'] = 0;
						}
						elseif(date("l",strtotime($start_week)) == 'Monday')
						{
							$data[$i]['x'] = 1;
						}
						elseif(date("l",strtotime($start_week)) == 'Tuesday')
						{
							$data[$i]['x'] = 2;
						}
						elseif(date("l",strtotime($start_week)) == 'Wednesday')
						{
							$data[$i]['x'] = 3;
						}
						elseif(date("l",strtotime($start_week)) == 'Thursday')
						{
							$data[$i]['x'] = 4;
						}
						elseif(date("l",strtotime($start_week)) == 'Friday')
						{
							$data[$i]['x'] = 5;
						}
						elseif(date("l",strtotime($start_week)) == 'Saturday')
						{
							$data[$i]['x'] = 6;
						}
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
					$data[$i]['x'] = $i;
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
				for($i=0;$i<=12;$i++)
				{		
						if(date("M",strtotime($date)) == 'Jan')
						{
							$data[$i]['x'] = 0;
						}
						elseif(date("M",strtotime($date)) == 'Feb')
						{
							$data[$i]['x'] = 1;
						}
						elseif(date("M",strtotime($date)) == 'Mar')
						{
							$data[$i]['x'] = 2;
						}
						elseif(date("M",strtotime($date)) == 'Apr')
						{
							$data[$i]['x'] = 3;
						}
						elseif(date("M",strtotime($date)) == 'May')
						{
							$data[$i]['x'] = 4;
						}
						elseif(date("M",strtotime($date)) == 'Jun')
						{
							$data[$i]['x'] = 5;
						}
						elseif(date("M",strtotime($date)) == 'Jul')
						{
							$data[$i]['x'] = 6;
						}
						elseif(date("M",strtotime($date)) == 'Aug')
						{
							$data[$i]['x'] = 7;
						}
						elseif(date("M",strtotime($date)) == 'Sep')
						{
							$data[$i]['x'] = 8;
						}
						elseif(date("M",strtotime($date)) == 'Oct')
						{
							$data[$i]['x'] = 9;
						}
						elseif(date("M",strtotime($date)) == 'Nov')
						{
							$data[$i]['x'] = 10;
						}
						elseif(date("M",strtotime($date)) == 'Dec')
						{
							$data[$i]['x'] = 11;
						}
						
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
			$access_token = str_replace('Bearer ','',$Authorization);
			$dates = date('Y-m-d');
			$averageglucose = number_format(Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->sum('cal'),2);
			$noonfasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>[2,3]])->average('glucosevalue');
			$fasting = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token,'readingid'=>1])->average('glucosevalue');
			if($get['type'] == 'day')
			{
				$morningavg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'breakfast' ])->average('cal');
				$data[0]['x'] = 0;
				$data[0]['name'] = "Breakfast";
				$data[0]['breakfast'] = ceil($morningavg);
				$afternoonavg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'lunch' ])->average('cal');
				$data[1]['x'] = 1;
				$data[1]['name'] = "Lunch";
				$data[1]['breakfast'] = ceil($afternoonavg);
				$dinneravg = Usermeals::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'mealtype'=>'dinner' ])->average('cal');
				$data[2]['x'] = 2;
				$data[2]['name'] = "Dinner";
				$data[2]['breakfast'] = ceil($dinneravg);
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
						if(date("l",strtotime($start_week)) == 'Sunday')
						{
							$data[$i]['x'] = 0;
						}
						elseif(date("l",strtotime($start_week)) == 'Monday')
						{
							$data[$i]['x'] = 1;
						}
						elseif(date("l",strtotime($start_week)) == 'Tuesday')
						{
							$data[$i]['x'] = 2;
						}
						elseif(date("l",strtotime($start_week)) == 'Wednesday')
						{
							$data[$i]['x'] = 3;
						}
						elseif(date("l",strtotime($start_week)) == 'Thursday')
						{
							$data[$i]['x'] = 4;
						}
						elseif(date("l",strtotime($start_week)) == 'Friday')
						{
							$data[$i]['x'] = 5;
						}
						elseif(date("l",strtotime($start_week)) == 'Saturday')
						{
							$data[$i]['x'] = 6;
						}
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
					$data[$i]['x'] = $i;
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
		    	for($i=0;$i<=12;$i++)
				{			
						if(date("M",strtotime($date)) == 'Jan')
						{
							$data[$i]['x'] = 0;
						}
						elseif(date("M",strtotime($date)) == 'Feb')
						{
							$data[$i]['x'] = 1;
						}
						elseif(date("M",strtotime($date)) == 'Mar')
						{
							$data[$i]['x'] = 2;
						}
						elseif(date("M",strtotime($date)) == 'Apr')
						{
							$data[$i]['x'] = 3;
						}
						elseif(date("M",strtotime($date)) == 'May')
						{
							$data[$i]['x'] = 4;
						}
						elseif(date("M",strtotime($date)) == 'Jun')
						{
							$data[$i]['x'] = 5;
						}
						elseif(date("M",strtotime($date)) == 'Jul')
						{
							$data[$i]['x'] = 6;
						}
						elseif(date("M",strtotime($date)) == 'Aug')
						{
							$data[$i]['x'] = 7;
						}
						elseif(date("M",strtotime($date)) == 'Sep')
						{
							$data[$i]['x'] = 8;
						}
						elseif(date("M",strtotime($date)) == 'Oct')
						{
							$data[$i]['x'] = 9;
						}
						elseif(date("M",strtotime($date)) == 'Nov')
						{
							$data[$i]['x'] = 10;
						}
						elseif(date("M",strtotime($date)) == 'Dec')
						{
							$data[$i]['x'] = 11;
						}
						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$morningavg = Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealtype'=>'breakfast'])->average('cal');
						$afternoonavg = Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealtype'=>'lunch'])->average('cal');
						$dinneravg = Usermeals::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealtype'=>'dinner'])->average('cal');
						//$data[$i]['x'] = $i;
						$data[$i]['breakfast'] = ceil($morningavg);
						$data[$i]['lunch'] = ceil($afternoonavg);
						$data[$i]['dinner'] = ceil($dinneravg);	
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'dates'=>$dates,'data'=>$data];
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
			$access_token = str_replace('Bearer ','',$Authorization);
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
			$access_token = str_replace('Bearer ','',$Authorization);
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
			$access_token = str_replace('Bearer ','',$Authorization);
			$dates = date('Y-m-d');
			$averageglucose = (Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('SystolicValue')).'/'.(Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('DiastolicValue'));
			
			if($get['type'] == 'day')
			{
				$morningsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('SystolicValue');
				$morningdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue');
				$data[0]['x'] = 0;
				$data[0]['name'] = "Break Fast";
				$data[0]['diastolic'] =ceil($morningdiaavg);
				$data[0]['Systolic'] = ceil($morningsysavg);
				
				$afternoonsysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('SystolicValue');
				$afternoondiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','12:00'])->andWhere(['<','time','19:00'])->average('DiastolicValue');
				
				$data[1]['x'] = 1;
				$data[1]['name'] = "Lunch";
				$data[1]['diastolic'] =ceil($afternoondiaavg);
				$data[1]['Systolic'] = ceil($afternoonsysavg);
				$dinnersysavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('SystolicValue');
				$dinnerdiaavg = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->andWhere(['>','time','19:00'])->average('DiastolicValue');
				
				$data[2]['x'] = 2;
				$data[2]['name'] = "Dinner";
				$data[2]['diastolic'] =ceil($dinnerdiaavg);
				$data[2]['Systolic'] = ceil($dinnersysavg);				
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
						if(date("l",strtotime($start_week)) == 'Sunday')
						{
							$data[$i]['x'] = 0;
						}
						elseif(date("l",strtotime($start_week)) == 'Monday')
						{
							$data[$i]['x'] = 1;
						}
						elseif(date("l",strtotime($start_week)) == 'Tuesday')
						{
							$data[$i]['x'] = 2;
						}
						elseif(date("l",strtotime($start_week)) == 'Wednesday')
						{
							$data[$i]['x'] = 3;
						}
						elseif(date("l",strtotime($start_week)) == 'Thursday')
						{
							$data[$i]['x'] = 4;
						}
						elseif(date("l",strtotime($start_week)) == 'Friday')
						{
							$data[$i]['x'] = 5;
						}
						elseif(date("l",strtotime($start_week)) == 'Saturday')
						{
							$data[$i]['x'] = 6;
						}
						$data[$i]['name'] = date("l",strtotime($start_week));
						$morningsysavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('DiastolicValue');
				      
				
						$data[$i]['diastolic'] = ceil($morningdiaavg);
						$data[$i]['Systolic'] = ceil($morningsysavg);
						
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
					$data[$i]['x'] = $i;
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
					$data[$i]['name'] = $name;					
					$morningsysavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('SystolicValue');
				    $morningdiaavg = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('DiastolicValue');
//$data[$i]['avgbp'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
					$data[$i]['diastolic'] = ceil($morningdiaavg);
					$data[$i]['Systolic'] = ceil($morningsysavg);
									
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
						if(date("M",strtotime($date)) == 'Jan')
						{
							$data[$i]['x'] = 0;
						}
						elseif(date("M",strtotime($date)) == 'Feb')
						{
							$data[$i]['x'] = 1;
						}
						elseif(date("M",strtotime($date)) == 'Mar')
						{
							$data[$i]['x'] = 2;
						}
						elseif(date("M",strtotime($date)) == 'Apr')
						{
							$data[$i]['x'] = 3;
						}
						elseif(date("M",strtotime($date)) == 'May')
						{
							$data[$i]['x'] = 4;
						}
						elseif(date("M",strtotime($date)) == 'Jun')
						{
							$data[$i]['x'] = 5;
						}
						elseif(date("M",strtotime($date)) == 'Jul')
						{
							$data[$i]['x'] = 6;
						}
						elseif(date("M",strtotime($date)) == 'Aug')
						{
							$data[$i]['x'] = 7;
						}
						elseif(date("M",strtotime($date)) == 'Sep')
						{
							$data[$i]['x'] = 8;
						}
						elseif(date("M",strtotime($date)) == 'Oct')
						{
							$data[$i]['x'] = 9;
						}
						elseif(date("M",strtotime($date)) == 'Nov')
						{
							$data[$i]['x'] = 10;
						}
						elseif(date("M",strtotime($date)) == 'Dec')
						{
							$data[$i]['x'] = 11;
						}
						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						
						$morningsysavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('SystolicValue');
				        $morningdiaavg = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('DiastolicValue');
				        
						//$data[$i]['avgbp'] = ceil($morningsysavg).' / '.ceil($morningdiaavg);
						//$data[$i]['lunch'] = ceil($afternoonsysavg).' / '.ceil($afternoondiaavg);
						//$data[$i]['dinner'] = ceil($dinnersysavg).' / '.ceil($dinnerdiaavg);
						$data[$i]['diastolic'] = ceil($morningdiaavg);
						$data[$i]['Systolic'] = ceil($morningsysavg);
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','averageglucose'=>$averageglucose,'dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionWeightchart()
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
			$access_token = str_replace('Bearer ','',$Authorization);
			$dates = date('Y-m-d');
			$bmiarray = [];
			$height = Bmivalues::find()->where(['access_token'=>$access_token])->one()->height;
			if($get['type'] == 'day')
			{
				$weightavg = Bmivalues::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->average('weight');
				$data[0]['name'] = date('Y-m-d');
				$data[0]['weight'] = number_format($weightavg,1);
				$data[0]['x'] = 1;
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
						$data[$i]['x'] = $i;
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
					$data[$i]['x'] = $i;
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
						$data[$i]['x'] = $i;
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$weightavg = Bmivalues::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('weight');
						$data[$i]['weight'] = number_format($weightavg,1);
						$bmiavg = Bmivalues::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('BMI');
						$bmiarray[$i]['name'] = date("M",strtotime($date));
						$bmiarray[$i]['BMI'] = number_format($bmiavg,1);
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data,'height'=>$height,'bmiarray'=>$bmiarray];
	   }
	}
	public static function actionBmichart()
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
			$access_token = str_replace('Bearer ','',$Authorization);
			$dates = date('Y-m-d');
			if($get['type'] == 'day')
			{
				$bmiavg = Bmivalues::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->average('BMI');
				$data[0]['x'] = 1;
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
						$data[$i]['x'] = $i;
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
					$data[$i]['x'] = $i;
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
						$data[$i]['x'] = $i;
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
	
	public function actionProfileView()    {
		$get = Yii::$app->request->get();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $Authorization = Yii::$app->request->headers->get('Authorization');				
		if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
        else
        {
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
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $profile = Franchies::find()->where(['userId' => $user->id])->asArray()->one();
				$profile['email'] = $user->email;
				return ['status' => true, 'message' => 'Profile View', 'data' => $profile];             
            }
         } 
      }    
	}
	
	public function actionUpdateprofile()    {
        $model = new Franchies();
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
                $profile = Franchies::find()->where(['userId' => $user->id])->one();
                if(!empty($profile))
                {               
					if(!empty($post['name']))
					{					
						$profile->name =  $post['name']; 
					}
					if(!empty($post['age']))
					{
						$profile->age =  $post['age'];
					}	
                    if(!empty($post['gender']))
					{					
                      $profile->gender = $post['gender']; 
					}
					if(!empty($post['partnerType']))
					{					
                      $profile->partnerType = $post['partnerType']; 
					}
					if(!empty($post['partner']))
					{					
                      $profile->partner = $post['partner']; 
					}
					if(!$profile->save())
					{
                        print_r($profile->errors);exit;
                    }
                    return ['status' => true, 'message' => 'Updated successfully'];
                }       
           }
        }
    }  

	public function actionPaymentlink(){
		$post = yii::$app->request->post();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $paymentdata = array();
        $productinfo = '';
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
			$url = 'https://employeehealthbenefits.apollohl.in/ccavenue-pg/api/auth/token';
			$ch = curl_init($url);
			$data = array(
					'username' => 'web_user',
					'password' => 'password',
					'grantType' => 'client_credentials',
					'source' => 'WEB'
			);
			$payload = json_encode($data);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = json_decode(curl_exec($ch));
			curl_close($ch);
			if($result->isActive == 1)
			{
				
				$orderId = 'ORDER-'.$post['id'];
				$userplan = Userplans::find()->where(['userPlanId'=>$post['id']])->one();
				$user = User::find()->where(['access_token'=>$userplan->access_token])->one();
				$plan = Plans::find()->where(['planId'=>$userplan->planId])->one();
				$userprofile = Userprofile::find()->where(['access_token'=>$userplan->access_token])->one();
				$paymentdata = array(
                    'orderId' => $orderId,
                    //'totalAmount' => $userplan->price,
					'totalAmount' => 1,
					'appointmentDate' => $userplan->planExpiryDate,
                    'name' => $userprofile->firstName,
                    'email' => $userprofile->firstName.'@gmail.com',
                    'mobile' => $user->username,
                    'source' => "web_user",
                    'productInfo' => "Apollo Sugar",
                    'successUrl'=>Yii::$app->request->hostInfo."/ApolloSugar/frontend/web/index.php?r=plan/paymentstatus&status=success",
                    'cancelUrl'=>Yii::$app->request->hostInfo."/ApolloSugar/frontend/web/index.php?r=plan/paymentstatus&status=cancel"
                );
				  //$purl = 'https://uatapp.apollohl.in/ccavenue-pg/api/payment/get-pay-link';
				  $purl = 'https://employeehealthbenefits.apollohl.in/ccavenue-pg/api/payment/get-pay-link';
				  $pch = curl_init($purl);
				  $payload1 = json_encode($paymentdata,JSON_NUMERIC_CHECK);
				  $headr = array();
				  $headr[] = 'Content-type: application/json';
				  $headr[] = 'Authorization:'. $result->token_type.' '.$result->access_token;
				  curl_setopt($pch, CURLOPT_POSTFIELDS, $payload1);
				  curl_setopt($pch, CURLOPT_HTTPHEADER, $headr);
				  curl_setopt($pch, CURLOPT_RETURNTRANSFER, true);
				  $result1 = json_decode(curl_exec($pch));
				  //print_r($payload1);exit;
				  curl_close($pch);	
				 // $message = "Dear ".$userprofile->firstName.", we at Apollo will help our best to help you manage diabetes. To complete the".$plan->PlanName." program subscription, please complete the payment through this link ".$result1->payLink;
				 // $message = "Dear ".$userprofile->firstName.", we at Apollo will help our best to help you manage diabetes. To complete the".$plan->PlanName." program subscription, please complete the payment through this link ".$result1->payLink." Team Apollo Sugar";
				 // $url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOSUG&dest_mobileno='.$user->username.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
				 // print_r($url);exit;
				  $tch = curl_init();  
				  $timeout = 5;
				  curl_setopt($tch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$result1->payLink);
				  curl_setopt($tch,CURLOPT_RETURNTRANSFER,1);  
				  curl_setopt($tch,CURLOPT_CONNECTTIMEOUT,$timeout);  
				  $tdata = curl_exec($tch);  
				  curl_close($tch);
				  //print_r($tdata);exit;
				  $message = "Dear ".$userprofile->firstName.", we at Apollo will help our best to help you manage diabetes. To complete the".$plan->PlanName." program subscription, please complete the payment through this link ".$tdata." Team Apollo Sugar";
				 //$message = "Dear ".$userprofile->firstName.", Your token number is ".$plan->PlanName." . For information regarding services at Apollo ".$plan->PlanName." Clinic click ".$tdata.". Apollo Clinic.";
				  $url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$user->username.'&message='.urlencode($message).'&response=Y';
				  $crl = curl_init();
				  curl_setopt($crl, CURLOPT_URL, $url);
				  curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
				  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);				 
				  $res = curl_exec($crl);
				  //print_r($url);exit;
		          return ['status' => true, 'message' => 'Success','link'=>$result1->payLink];				 
			}
			else
			{
				return ['status' => false, 'message' => $result->message];	
			}
	    }
    }
	
	public function actionReports(){
		$post = yii::$app->request->post();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $paymentdata = array();
        $productinfo = '';
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
			//$url = 'http://uatlims.apollohl.in/HomeCollectionDynamicRoaster/API/LoginAPIDynamic';
			$url ="http://report.apollodiagnostics.in/HomeCollection/API/LoginAPI";
			$ch = curl_init($url);
			$data = array(
					'Username' => 'pup_regis',
					//'Password' => 'ADCY-GTRF-DFSC',
					'Password' => 'PROD-GTRY-UEDG',
					'Client' => 'DigitalTeam'					
			);			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = json_decode(curl_exec($ch));
			curl_close($ch);
			if($result->status == 1)
			{
				
				//$purl = 'http://uatlims.apollohl.in/HomeCollectionDynamicRoaster/API/Registration/GetReportUrl';
				$purl="http://report.apollodiagnostics.in/HomeCollection/API/Registration/GetReportUrl";
				$pch = curl_init($purl);
				$headr = array();
				$headr[] =  "Content-Type: application/x-www-form-urlencoded";
				$headr[] = 'Authorization:'. 'Bearer '.$result->data[0]->Token;
				$VisitID = "VisitID=".$post['VisitID'];
				curl_setopt($pch, CURLOPT_HTTPHEADER, $headr);
				curl_setopt($pch, CURLOPT_POST, true);
				curl_setopt($pch, CURLOPT_POSTFIELDS, $VisitID );				
				curl_setopt($pch, CURLOPT_RETURNTRANSFER, true);
				$result1 = json_decode(curl_exec($pch));
				print_r($result1);exit;
				curl_close($pch);				
		        return ['status' => true, 'message' => 'Success','link'=>$result1->message];				 
			}
			else
			{
				return ['status' => false, 'message' => $result->message];	
			}
	    }
    }
	
	
	public function actionViewreports(){
		$post = yii::$app->request->post();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $paymentdata = array();
        $productinfo = '';
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
				$purl = 'http://uatlims.apollohl.in/ApolloLive/design/onlinelab/reportapi.aspx';
				$pch = curl_init($purl);
				$headr = array();
				$headr[] =  "Content-Type: application/x-www-form-urlencoded";
				$VisitID = "Username='Digitalteam'&Password='DIGCTDVNPVYVATULPRT'&VisitNo=".$post['VisitID'];
				curl_setopt($pch, CURLOPT_HTTPHEADER, $headr);
				curl_setopt($pch, CURLOPT_POST, true);
				curl_setopt($pch, CURLOPT_POSTFIELDS, $VisitID );				
				curl_setopt($pch, CURLOPT_RETURNTRANSFER, true);
				$result1 = json_decode(curl_exec($pch));
				print_r($result1);exit;
				curl_close($pch);				
		        return ['status' => true, 'message' => 'Success','link'=>$result1->message];				 
			
	    }
    }
	
	
	public function actionPaymentstatus($status)
	{
		if($_POST['status'] == 'Success')
		{
			//print_r($_POST);exit;
			$orderId = str_replace('ORDER-','',$_POST['orderNo']);
			$userplan = Userplans::find()->where(['userPlanId'=>$orderId])->one();
			$user = User::find()->where(['access_token'=>$userplan->access_token])->one();
			$userplan->Status = 'Subcribed';
			$userplan->txnId = $_POST['txnId'];
			$userplan->save();
			$purl ="https://play.google.com/store/apps/details?id=com.clinics.apollosugar";
			$tch = curl_init();  
			$timeout = 5;
			curl_setopt($tch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$purl);
			curl_setopt($tch,CURLOPT_RETURNTRANSFER,1);  
			curl_setopt($tch,CURLOPT_CONNECTTIMEOUT,$timeout);  
			$tdata = curl_exec($tch);  
			curl_close($tch);			
			//$message = "Dear ".$user->username.", Your token number is ".$user->username." . For information regarding services at Apollo ".$user->username." Clinic click ".$tdata.". Apollo Clinic.";
			$message = "Dear Customer, if you would like to treat your diabetes under supervision, download the app now or click the link https://play.google.com/store/apps/details?id=com.clinics.apollosugar";
			$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$user->username.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
			$crl = curl_init();
			curl_setopt($crl, CURLOPT_URL, $url);
			curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
			$res = curl_exec($crl);
			return ['status' => true, 'message' => 'Success'];			
		}
		else
		{
			return ['status' => true, 'message' => $_POST['status']];
		}
    }
}