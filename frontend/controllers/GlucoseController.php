<?php 
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Glucose;
use frontend\models\Avgglucose;
use common\models\User;
use backend\modules\common\models\Mealtype;
use backend\modules\common\models\Readingtype;
use backend\modules\common\models\Fooditems;
use backend\modules\common\models\Fooditemdetails;
use backend\modules\users\models\Usermeals;
use backend\modules\common\models\Portions;
use backend\modules\plans\models\Userexcercisetrack;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use backend\modules\clinics\models\Clinics;
use frontend\models\Steptracker;
use frontend\models\Userprofile;
use backend\modules\notifications\models\Notifications;
use frontend\models\Login;
use backend\modules\common\models\Bp;
use frontend\models\Customernotifications;
class GlucoseController extends Controller
{
	public static function actionAccesstoken()	{
		$users = User::find()->where(['>','id','204'])->all();
		foreach($users as $key=>$value)
		{
			$user = User::find()->where(['id'=>$value->id])->one();
			$user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
			$user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
			$user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
			$user->save();
		}
	}
    public static function actionIndex()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
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
                $model = Glucose::find()->where(['access_token'=>str_replace('Bearer ','',$Authorization)])->all();
                return ['status' => true, 'message' => 'Glucose Data', 'data' => $model]; 
            }  
    }
    public static function actionReadingTypes()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
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
                $model = Readingtype::find()->all();
                return ['status' => true, 'message' => 'Readingtype Data', 'data' => $model]; 
            }  
    }
	public static function actionCities()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
        $get = Yii::$app->request->get();
		$model = City::find()->all();
        return ['status' => true, 'message' => 'Cities Data', 'data' => $model]; 
             
    }
	public static function actionLocations()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
        $get = Yii::$app->request->get();
		$model = Location::find()->where(['cid'=>$_GET['city']])->all();
        return ['status' => true, 'message' => 'Location Data', 'data' => $model]; 
              
    }
    public static function actionClinics()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
        $get = Yii::$app->request->get();
		if(isset($_GET['city']) && !empty($_GET['city']))
		{			
			$model = Clinics::find()->where(['cityId'=>$_GET['city']])->all();
		}
		else
		{
			$model = Clinics::find()->all();
		}
        return ['status' => true, 'message' => 'Clinics Data', 'data' => $model];             
    }
	public static function actionMealTypes()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
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
                $model = Mealtype::find()->all();
                return ['status' => true, 'message' => 'Mealtype Data', 'data' => $model]; 
            }  
    }
    public static function actionAddGlucose()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
		date_default_timezone_set("Asia/Calcutta");
        if ($model->load(\Yii::$app->request->post(), '') ) 
        {
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
     		    $post = Yii::$app->request->post();
				$access_token = str_replace('Bearer ','',$Authorization);
				$model->access_token = $access_token;
                $model->readingid = $model->readingid;
                $model->readingType = $model->readingType;
				$model->mealid = $model->mealid;
                $model->mealtype = $model->mealtype;
				$model->pickdate = date('Y-m-d');
                $model->createdDate = date('Y-m-d');
                $model->updatedDate = date('Y-m-d');
                $model->glucosevalue = $post['glucosevalue'];
				if($model->glucosevalue >=80 && $model->glucosevalue <=130){
					$model->Status = 'Normal'; 
				}				
				if($model->glucosevalue >=54 && $model->glucosevalue <=80){
					$model->Status = 'Moderate'; 
				}
				
				if($model->glucosevalue >=130 && $model->glucosevalue <=181){
					$model->Status = 'Moderate'; 
				}
				if($model->glucosevalue < 54 || $model->glucosevalue >181){
					$model->Status = 'Danger'; 
				}	
				$model->save();
				$Avgglucose = Avgglucose::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d')])->one();
				if(!empty($Avgglucose))
				{
					$averageglucose = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token' => $access_token])->average('glucosevalue');				
					$Avgglucose->avgGlucoseValue = $averageglucose;
					$Avgglucose->updatedDate = date('Y-m-d');
					$Avgglucose->deviceUsed = 'Vitals';
					$Avgglucose->save();
				}
				else
				{
					$Avgglucose = new Avgglucose();
					$Avgglucose->avgGlucoseValue = $model->glucosevalue;
					$Avgglucose->access_token = $access_token;
					$Avgglucose->createdDate = date('Y-m-d');
					$Avgglucose->updatedDate = date('Y-m-d');
					$Avgglucose->deviceUsed = 'Vitals';
					$Avgglucose->save();
				}
				$data['glucosestatus'] = $model->Status;
				$data['glucosevalue'] = $model->glucosevalue;
				$data['readingType'] = $model->readingType;
                return ['status' => true, 'message' => 'Glucose Added Successfully','data'=>$data]; 
            }            
        }
    }
	public static function actionGlucosetrackchart()	{
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
				$records = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token,'readingid'=>$get['readingid'] ])->all();
				foreach($records as $key=>$value)
				{
					$data[$key]['name'] = date('g:i A',strtotime($value->time));
					$data[$key]['pv'] = intval($value->glucosevalue);
				}	
			}
			if($get['type'] == 'week')
			{
				$previous_week = strtotime("-1 week +1 day");
				$d = date("l");
				$start_week = strtotime("last ".$d." midnight",$previous_week);				
				$start_week = date("Y-m-d",$start_week);
				$end_week = date("Y-m-d");
				$dates = $start_week.' To '.$end_week;
				$records = Glucose::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'readingid'=>$get['readingid']])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] =  date("l",strtotime($value->createdDate));
						$recordnew = Glucose::find()->where(['createdDate'=>$value->createdDate,'access_token'=>$access_token])->average('glucosevalue');
						$data[$key]['pv'] = ceil($recordnew);
				}				
			}
			if($get['type'] == 'month')
			{
				$yourMonth = date('m') -1;
				$currentMonth = date('m');				
				$firstweekstart = date('Y').'-'.$yourMonth.'-'.date('d');
				$firstweekend = date('Y-m-d',strtotime("+7 day", strtotime($firstweekstart)));
				$dates = $firstweekstart.' To '.date('Y-m-d');
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
					$recordnew = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'readingid'=>$get['readingid']])->average('glucosevalue');
					$data[$i]['pv'] = ceil($recordnew);	
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				for($i=0;$i<=12;$i++)
				{						
						$data[$i]['name'] = date("M",strtotime($date));
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$recordnew = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'readingid'=>$get['readingid']])->average('glucosevalue');
						$data[$i]['pv'] = ceil($recordnew);
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionMealitems()    {
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
                $model = Fooditems::find()->where(['Status'=>"Active"])->AsArray()->all();
				foreach($model as $key=>$value)
				{
					$items = Fooditemdetails::find()->where(['itemId'=>$value['itemId']])->all();
					$value['calories'] = $items;
					$data[] = $value;
				}					
                return ['status' => true, 'message' => 'Items Data', 'data' => $data]; 
            }  
    }
	public static function actionMealitemdetails()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $post = Yii::$app->request->post();
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
                $model = Fooditemdetails::find()->where(['itemId'=>$post['itemId']])->all();
                return ['status' => true, 'message' => 'Items Details', 'data' => $model]; 
            }  
    }
	public static function actionAddMeals()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Usermeals();
        $data = array();
		date_default_timezone_set("Asia/Calcutta");
        if ($model->load(\Yii::$app->request->post(), '') ) 
        {
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
				$access_token = str_replace('Bearer ','',$Authorization);
				$portion = Portions::find()->where(['portionId'=>$model->qunatity])->one();
				$post = Yii::$app->request->post();
				$model->access_token = $access_token;
				$model->cal = $post['cal'];
				$model->carbohydrates = $post['carbohydrates'];
				$model->fat = $post['fat'];
				$model->fiber = $post['fiber'];
				$model->proteins = $post['proteins'];
				$model->portionId = $model->qunatity;
				$model->qunatity = $portion->portionName;
				$model->createdDate= date('Y-m-d');
				//print_r($model);exit;
                $model->save();
                return ['status' => true, 'message' => 'Meal Added Successfully']; 
            }            
        }
    }	
	public static function actionAddExcercise()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Userexcercisetrack();
        $data = array();
		date_default_timezone_set("Asia/Calcutta");
        if ($model->load(\Yii::$app->request->post(), '') ) 
        {
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
				$access_token = str_replace('Bearer ','',$Authorization);
				$post = Yii::$app->request->post();
				$model->access_token = $access_token;
				$model->time = date("H:i");
				$model->createdDate= date('Y-m-d');
				$model->excerciseId = $post['excerciseId'];
				$model->title = $post['title'];
				$model->distance = $post['distance'];
		        $model->save();
                return ['status' => true, 'message' => 'Excercise Added Successfully']; 
            }            
        }
    }
	public static function actionUsermeals()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $post = Yii::$app->request->post();
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
                $model = Usermeals::find()->where(['access_token'=>$access_token])->AsArray()->all();
                return ['status' => true, 'message' => 'items list', 'data' => $model]; 
            }  
    }	
	public static function actionAddDeviceIntegrationbkp()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
		date_default_timezone_set("Asia/Calcutta");
        if ($model->load(\Yii::$app->request->post(), '') ) 
        {
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
     			$access_token = str_replace('Bearer ','',$Authorization);
				$post = Yii::$app->request->post();
				print_r($post);exit;
				for($i=0;$i<count($post['data']);$i++){
					$date = $post['data'][$i]['glucose_data']['blood_glucose_samples'][0]['timestamp'];
					$date = date('Y-m-d',strtotime($date));
					$avgglucose = Avgglucose::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
					if(!empty($avgglucose))
					{
						$avgglucose->avgGlucoseValue = $post['data'][$i]['glucose_data']['day_avg_blood_glucose_mg_per_dL'];
						$avgglucose->updatedDate = $date;
						$avgglucose->save();
					}
					else
					{
						$gmodel = new Avgglucose();
						$gmodel->access_token = $access_token;
						$gmodel->avgGlucoseValue = $post['data'][$i]['glucose_data']['day_avg_blood_glucose_mg_per_dL'];
						$gmodel->updatedDate = $date;
						$gmodel->createdDate = $date;
						$gmodel->deviceUsed = "Thera";
						if(!$gmodel->save())
						{
							print_r($gmodel->errors);exit;
						}
					}
					Glucose::deleteAll(['access_token'=>$access_token,'createdDate'=>$date]);
					foreach($post['data'][$i]['glucose_data']['blood_glucose_samples'] as $key=>$value)
					{
						$newmodel = new Glucose();
						$newmodel->access_token = $access_token;
						$newmodel->glucosevalue = $value['blood_glucose_mg_per_dL'];
						$newmodel->createdDate = $date;
						$newmodel->updatedDate = $date;						
						if($newmodel->glucosevalue >=80 && $newmodel->glucosevalue <=130){
							$newmodel->Status = 'Normal'; 
						}						
						if($newmodel->glucosevalue >=54 && $newmodel->glucosevalue <=80){
							$newmodel->Status = 'Moderate'; 
						}						
						if($newmodel->glucosevalue >=130 && $newmodel->glucosevalue <=181){
							$newmodel->Status = 'Moderate'; 
						}						
						if($newmodel->glucosevalue < 54 || $newmodel->glucosevalue >181){
							$newmodel->Status = 'Danger'; 
						}	
						if(!$newmodel->save())
						{
							print_r($newmodel->errors);exit;
						}
					}
				}
				return ['status' => true, 'message' => 'Glucose Added Successfully']; 
            }            
        }
    }
	public static function actionAddDeviceIntegration()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Glucose();
        $data = array();
		date_default_timezone_set("Asia/Calcutta");
        if ($model->load(\Yii::$app->request->post(), '') ) 
        {
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
     			$access_token = str_replace('Bearer ','',$Authorization);
				$post = Yii::$app->request->post();
				foreach($post['data'] as $key=>$value)
				{
					$date = date('Y-m-d',strtotime(str_replace(" ","",$value['dateFrom'])));
					$time = date('H:i',strtotime(str_replace(" ","",$value['dateFrom'])));
					if($value['type'] == "HealthDataType.BLOOD_GLUCOSE")
					{
						
						$avgglucose = Avgglucose::find()->where(['access_token'=>$access_token,'createdDate'=>$date])->one();
						if(!empty($avgglucose))
						{
							$avgglucose->avgGlucoseValue = $value['value'];
							$avgglucose->updatedDate = $date;
							$avgglucose->save();
						}
						else
						{
							$gmodel = new Avgglucose();
							$gmodel->access_token = $access_token;
							$gmodel->avgGlucoseValue = $value['value'];
							$gmodel->updatedDate = $date;
							$gmodel->createdDate = $date;
							$gmodel->deviceUsed = "Fitbit";
							if(!$gmodel->save())
							{
								print_r($gmodel->errors);exit;
							}
						}
						$newmodel = new Glucose();
						$newmodel->access_token = $access_token;
						$newmodel->glucosevalue = $value['value'];
						$newmodel->createdDate = $date;
						$newmodel->updatedDate = $date;						
						if($newmodel->glucosevalue >=80 && $newmodel->glucosevalue <=130){
								$newmodel->Status = 'Normal'; 
						}						
						if($newmodel->glucosevalue >=54 && $newmodel->glucosevalue <=80){
								$newmodel->Status = 'Moderate'; 
						}						
						if($newmodel->glucosevalue >=130 && $newmodel->glucosevalue <=181){
								$newmodel->Status = 'Moderate'; 
						}						
						if($newmodel->glucosevalue < 54 || $newmodel->glucosevalue >181){
								$newmodel->Status = 'Danger'; 
						}	
						if(!$newmodel->save())
						{
								print_r($newmodel->errors);exit;
						}
					}
					elseif($value['type'] == "HealthDataType.STEPS")
					{
						$steps = Steptracker::find()->where(['access_token'=>$access_token,'date'=>$date])->one();
						$user = Userprofile::find()->where(['access_token'=>$access_token])->one();
						
						$cal = 0;
						$distance = 0;
						if(!empty($steps))
						{
							$steps->count = $steps->count + $value['value'];							
							if(!empty($user))
							{
							  $stride = ($user->height*0.01)*0.414;
							  $distance = ($stride * $steps->count);
							  $time = $distance/1.35;
							  $cal = (($time * 3.5 * 3.5 * $user->weight)/(200 * 60));
							  $distance  = $distance /1000;							  
							}	
							$steps->cal = $cal;
							$steps->distance = $distance;
							$steps->updatedDate = $date;
							if(!($steps->save()))
							{
								print_r($steps->errors);exit;
							}
						}	
						else
						{
							$newmodel = new Steptracker();
							$newmodel->access_token = $access_token;
							$newmodel->count = $value['value'];
							if(!empty($user))
							{
							  $stride = ($user->height*0.01)*0.414;
							  $distance = ($stride * $newmodel->count);
							  $time = $distance/1.35;
							  $cal = (($time * 3.5 * 3.5 * $user->weight)/(200 * 60));
							  $distance  = $distance /1000;							  
							}
							$newmodel->steptype = 'walking';
							$newmodel->cal = $cal;
							$newmodel->distance = $distance;
							$newmodel->date = $date;
							$newmodel->createdDate = $date;
							$newmodel->updatedDate = $date;	
							//print_r($newmodel);exit;
							$newmodel->save();
						}
					}
					elseif($value['type'] == "HealthDataType.BLOOD_PRESSURE_SYSTOLIC")
					{	
						$sys = Bp::find()->where(['access_token'=>$access_token,'pickdate'=>$date,'time'=>$time])->one();
						if(!empty($sys))
						{
							$sys->SystolicValue = $value['value'];
							$sys->updatedDate = date('Y-m-d');	
							$sys->save();
						}
						else
						{
							$model = new Bp();
							$model->access_token = $access_token;
							$model->SystolicValue = $value['value'];
							$model->DiastolicValue = "";
							$model->time = $time;
							$model->pickdate = $date;
							$model->createdDate = date('Y-m-d');
							$model->updatedDate = date('Y-m-d');
							if(!$model->save())
							{
									print_r($model->errors);exit;
							}
						}
					}
					elseif($value['type'] == "HealthDataType.BLOOD_PRESSURE_DIASTOLIC")
					{	
						$dia = Bp::find()->where(['access_token'=>$access_token,'pickdate'=>$date,'time'=>$time])->one();
						if(!empty($dia))
						{
							$dia->DiastolicValue = $value['value'];
							$dia->updatedDate = date('Y-m-d');	
							$dia->save();
						}
						else
						{
						$model = new Bp();
						$model->access_token = $access_token;
						$model->DiastolicValue = $value['value'];
						$model->SystolicValue = "";
						$model->time = $time;
						$model->pickdate = $date;
						$model->createdDate = date('Y-m-d');
						$model->updatedDate = date('Y-m-d');
						if(!$model->save())
						{
								print_r($model->errors);exit;
						}
						}
					}
				}
				
			}
			return ['status' => true, 'message' => 'Glucose Added Successfully']; 
            }            
        
    }
	public static function actionGlucosenotifications()	{
		$users = User::find()->RightJoin('login','login.userId=user.id')->where(['roleId'=>null])->andWhere(['!=','id',1])->all();
		if(!empty($users))
		{
			foreach($users as $key=>$value)
			{
				
			}
		}
	}
	public static function actionSteptracker()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
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
				header('Access-Control-Allow-Origin: *');
				header('Access-Control-Allow-Methods: *');
				header('Access-Control-Allow-Headers: *');	
				$data = array();
				$post = Yii::$app->request->post();
				$access_token = str_replace('Bearer ','',$Authorization);
				//$stepcount = Steptracker::find()->where(['access_token'=>$access_token])->andwhere(['<=','createdDate',date('Y-m-d',strtotime('-1 days'))])->sum('count');
				//print_r($stepcount);exit;
				//$post['count'] = $post['count'] - $stepcount;
				$model = new Steptracker();
				$model->access_token = $access_token;
				$model->steptype = $post['steptype'];
				$model->date = date('Y-m-d');
				$model->count = $post['count'];
				$model->cal = $post['cal'];
				$model->distance = $post['distance'];
				$model->createdDate = date('Y-m-d');
				$model->updatedDate = date('Y-m-d');
				$user = Userprofile::find()->where(['access_token'=>$access_token])->one();
				$cal = 0;
				$distance = 0;
				if(!empty($user))
				{
				  $stride = ($user->height*0.01)*0.414;
				  $distance = ($stride * $post['count']);
				  $time = $distance/1.35;
				  $cal = (($time * 3.5 * 3.5 * $user->weight)/(200 * 60));
				  $distance  = $distance /1000;
				  
				}
				$count = Steptracker::find()->where(['access_token'=>$access_token,'steptype'=>$post['steptype'],'date'=>date('Y-m-d')])->one();
				
				if(empty($count))
				{
					$model->cal = $cal;
				    $model->distance = $distance;
					if(!$model->save())
					{
						print_r($model->errors);exit;
					}
				}
				else
				{					
					$count->cal = $cal;
				    $count->distance = $distance;
					$count->count =  $post['count'];
					$count->updatedDate = date('Y-m-d');
					$count->save();
				}
                return ['status' => true, 'message' => 'Steps Count Saved Successfully']; 
            }  
    }
	public static function actionSteptrackchart()    {
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
				$stepcount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('count');
				$calcount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('cal');
				$distancecount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('distance');
				$data[0]['x'] = 0;
				$data[0]['name'] = "Steps";
				$data[0]['count'] = ceil($stepcount);
                $data[0]['cal'] = ceil($calcount);
				$data[0]['distance'] = floatval($distancecount);
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
				while($start_week <= $end_week)
				{
						$recordnew = Steptracker::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('count');
						$calcount = Steptracker::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('cal');
						$distancecount = Steptracker::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token])->average('distance');
						
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
						$data[$i]['count'] = ceil($recordnew);
						$data[$i]['cal'] = ceil($calcount);
				        $data[$i]['distance'] = floatval($distancecount);
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
					$recordnew = Steptracker::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('count');
					$calcount = Steptracker::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('cal');
				    $distancecount = Steptracker::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('distance');				
					$data[$i]['count'] = ceil($recordnew);	
					$data[$i]['cal'] = ceil($calcount);
				    $data[$i]['distance'] = floatval($distancecount);
				}
			}
			if($get['type'] == 'year')
			{
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
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
						$recordnew = Steptracker::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('count');
						$calcount = Steptracker::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('cal');
						$distancecount = Steptracker::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->average('distance');
						$data[$i]['count'] = ceil($recordnew);
						$data[$i]['cal'] = ceil($calcount);
				        $data[$i]['distance'] = floatval($distancecount);
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionPushnotifications()		{
		$data = [];
		$logins = Login::find()->all();
		foreach($logins as $key=>$value)
		{
			$fcm_id[] = $value->gcm_id;
		}	
		$data['title'] = "Title";
		$data['body'] = "Body";
		$model = new Notifications();
		$send = $model->addUserFcm($data, $fcm_id);		
	}
	public static function actionGlucoseremainder()		{
		date_default_timezone_set("Asia/Calcutta");
		$data = [];
		$logins = Login::find()->all();
		$time = date('H:i');
		$data['title'] = "Title";
		$data['body'] = "Guess it's time to check your glucose levels, let's get checked now!";
		foreach($logins as $key=>$value)
		{			
			if($time > $_GET['time'])
			{
				$glucose = Glucose::find()->where(['mealtype'=>'breakfast','createdDate'=>date('Y-m-d')])->one();
				if(empty($glucose))
				{
					$newmodel  = new Customernotifications();
					$access_token = User::find()->where(['id'=>$value->userId])->one()->access_token;
					$newmodel->access_token = $access_token;
					$newmodel->message = $data['body'];
					$newmodel->time = $time;
					$newmodel->createdDate = date('Y-m-d');
					$newmodel->updatedDate = date('Y-m-d');
					$newmodel->save();
					$fcm_id[] = $value->gcm_id;
				}
			}			
		}	
		$model = new Notifications();
		$send = $model->addUserFcm($data, $fcm_id);		
	}
}
?>