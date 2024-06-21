<?php
namespace frontend\modules\react\controllers;
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
use backend\modules\users\models\Slots;
use backend\modules\users\models\Bmivalues;
use backend\modules\common\models\Bp;
use frontend\models\Glucose;
use frontend\models\Prescriptionpdfs;
use backend\modules\users\models\Usermeals;
use frontend\models\Userplans;
use backend\modules\packages\models\Planinclusions;
use backend\modules\packages\models\Plans;
use backend\modules\packages\models\Packages;
use backend\modules\packages\models\Packageitems;
use backend\modules\packages\models\ItemDetails;
use backend\modules\packages\models\Plandetails;
use backend\modules\packages\models\Doctordrivenlinks;
use backend\modules\notifications\models\Notifications;
use frontend\models\Doctorslotsession;
use frontend\models\Dieticianslotsession;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use frontend\models\Orders;
use frontend\models\Orderitems;
class PlanController extends Controller
{
    public static function actionPortions()
    {
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
                $model = Fooditemdetails::find()->where(['itemId'=>$_GET['itemId']])->all();
                return ['status' => true, 'message' => 'Portions Data', 'data' => $model]; 
            }  
    }
	public static function actionMedicineMaster()
    {
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
                $model = Medicinemaster::find()->where(['status'=>'Active','type'=>$_GET['type']])->all();
                return ['status' => true, 'message' => 'Medicines Data', 'data' => $model]; 
            }  
    }
	
	public static function actionLabTests()
    {
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
                $model = ItemDetails::find()->where(['status'=>'Active'])->andwhere(['!=','itemId','1'])->andwhere(['!=','itemId','2'])->all();
                return ['status' => true, 'message' => 'Labtests Data', 'data' => $model]; 
            }  
    }
	
	public static function actionUsages()
    {
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
                $model = Usagemaster::find()->where(['status'=>'Active'])->all();
                return ['status' => true, 'message' => 'Usagemaster Data', 'data' => $model]; 
            }  
    }
	
	public static function actionDurations()
    {
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
                $model = Durations::find()->where(['status'=>'Active'])->all();
                return ['status' => true, 'message' => 'Durations Data', 'data' => $model]; 
            }  
    }
	public static function actionAddDietPlans()
	{
		$data = array();
		$post = Yii::$app->request->post();
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
				    $d = strtotime("today");
					$currentstart_week = strtotime("last sunday midnight",$d);
					$currentend_week = strtotime("next saturday",$d);
					$start = date("Y-m-d",$currentstart_week); 
					$end = date("Y-m-d",$currentend_week);
					Dietplans::deleteAll(['userId' => $_GET['patId'],'createdBy'=>$user->id]);
					//print_r($post);exit;
					foreach($post['data'] as $key=>$value)
					{
							$model = new Dietplans();
							$model->foodtype = $post['foodtype'];
							$model->userId = $_GET['patId'];
							$value['time'] = date('H:i',strtotime($value['time']));
							$model->time   = $value['time'];						
							if($value['time'] < "11:00")
							{
								$model->mealtypeId = 1;
								$model->mealtype = "breakfast";
							}
							else if($value['time'] >= "11:00" && $value['time'] < "16:00")
							{
								$model->mealtypeId = 2;
								$model->mealtype = "lunch";
							}							
							else if($value['time'] >= "16:00")
							{
								$model->mealtypeId = 3;
								$model->mealtype = "dinner";
							}
							$model->createdBy = $user->id;
							$model->updatedBy = $user->id;
							$model->createdDate = $start;
							$model->updatedDate = $end;
							
							$diet = Dietplans::find()->where(['userId'=>$_GET['patId'],'mealtypeId'=>$model->mealtypeId,'mealtype'=>$model->mealtype,'createdDate'=>$start,'updatedDate'=>$end])->one();
							//print_r($diet);exit;
							if(empty($diet))
							{
								$model->save();
								$plan = $model->planId;
							}
							else
							{
								$plan = $diet->planId;
							}
							
							$cal = 0.0;
							$newmodel = new Dietplandetails();
							$newmodel->planId = $model->planId;
							$newmodel->itemId = $value['items']['itemId'];
							$newmodel->itemName = $value['items']['itemName'];
							$newmodel->quantity = $value['portion']['quantity'];
							$cal = $cal +  $value['portion']['cal'];
							$newmodel->calories = $cal;
							if(!$newmodel->save())
							{
								print_r($newmodel->errors);exit;
							}					
					
					}
					return ['status' => true, 'message' => 'Success'];
			}		
	}
	
	public static function actionAddremarks()
	{
		$data = array();
		$post = Yii::$app->request->post();
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
				   $access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
				   $model = new Remarks();
				   $model->text = $post['text'];
				   $model->access_token = $access_token;
				   $model->createdBy = $user->id;
				   $model->updatedBy = $user->id;
				   $model->createdDate = date('Y-m-d');
				   $model->updatedDate = date('Y-m-d');
					//print_r($model);exit;
				   $model->save();
				   return ['status' => true, 'message' => 'Success'];
			}		
	}
	public static function actionRemarks()
    {
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
				$access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
                $model = Remarks::find()->where(['access_token'=>$access_token,'createdBy'=>$user->id])->all();
                return ['status' => true, 'message' => 'Remarks Data', 'data' => $model]; 
            }  
    }
	
	public static function actionAddsymptoms()
	{
		$data = array();
		$post = Yii::$app->request->post();
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
				   $access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
				   $model = new Patientsymptoms();
				   $model->text = $post['text'];
				   $model->access_token = $access_token;
				   $model->createdBy = $user->id;
				   $model->updatedBy = $user->id;
				   $model->createdDate = date('Y-m-d');
				   $model->updatedDate = date('Y-m-d');
					//print_r($model);exit;
				   $model->save();
				   return ['status' => true, 'message' => 'Success'];
			}		
	}
	public static function actionSymptoms()
    {
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
				$access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
                $model = Patientsymptoms::find()->where(['access_token'=>$access_token,'createdBy'=>$user->id])->all();
                return ['status' => true, 'message' => 'Symptoms Data', 'data' => $model]; 
            }  
    }
	
	public static function actionAddallergies()
	{
		$data = array();
		$post = Yii::$app->request->post();
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
				   $access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
				   $model = new Patientallergies();
				   $model->text = $post['text'];
				   $model->access_token = $access_token;
				   $model->createdBy = $user->id;
				   $model->updatedBy = $user->id;
				   $model->createdDate = date('Y-m-d');
				   $model->updatedDate = date('Y-m-d');
					//print_r($model);exit;
				   $model->save();
				   return ['status' => true, 'message' => 'Success'];
			}		
	}
	public static function actionAllergies()
    {
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
				$access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
                $model = Patientallergies::find()->where(['access_token'=>$access_token,'createdBy'=>$user->id])->all();
                return ['status' => true, 'message' => 'Allergies Data', 'data' => $model]; 
            }  
    }
	
	
	public static function actionExcerciseCategories()
    {
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
                $model = Categories::find()->where(['Status'=>"Active",'type'=>'Excercise'])->all();
                return ['status' => true, 'message' => 'Excercise Categories Data', 'data' => $model]; 
            }  
    }
	
	public static function actionExcercises()
    {
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
                $model = Excercise::find()->where(['Status'=>"Active",'categoryId'=>$_GET['id']])->all();
                return ['status' => true, 'message' => 'Excercise Categories Data', 'data' => $model]; 
            }  
    }
	
	public static function actionAddExcercisePlans()
	{
		$data = array();
		$post = Yii::$app->request->post();
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
				    $d = strtotime("today");
					$currentstart_week = strtotime("last sunday midnight",$d);
					$currentend_week = strtotime("next saturday",$d);
					$start = date("Y-m-d",$currentstart_week); 
					$end = date("Y-m-d",$currentend_week);
					
					Excerciseplans::deleteAll(['userId' => $_GET['patId'],'createdBy'=>$user->id,'createdDate'=>$start,'updatedDate'=>$end]);
					//print_r($post);exit;
					foreach($post['data'] as $key=>$value)
					{
							$model = new Excerciseplans();
							$model->title = $value['duration'];
							$model->userId = $_GET['patId'];							
							//$value['time'] =  str_replace("GMT+0530 (India Standard Time)"," ",$value['time']);
							$value['time'] = date('H:i',strtotime($value['time']));
							$model->time   = $value['time'];						
							$model->createdBy = $user->id;
							$model->updatedBy = $user->id;
							$model->createdDate = $start;
							$model->updatedDate = $end;
							//print_r($model);exit;
							$model->save();
							
							$newmodel = new Excerciseplandetails();
							$newmodel->explanId = $model->explanId;
							$newmodel->excerciseId = $value['exercises']['ExcerciseId'];
							$newmodel->title = $value['exercises']['title'];
							$newmodel->distance = $value['duration'];
							if(!$newmodel->save())
								{
									print_r($newmodel->errors);exit;
								}
							
							
					
					}
					return ['status' => true, 'message' => 'Success'];
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
					}					
					$plans = Excerciseplans::find()->where(['userId'=>$get['userId'],'createdDate'=>$start,'updatedDate'=>$end])->all();
					$newdata = [];
					if(!empty($plans))
					{
						foreach($plans as $key=>$value)
						{
							$data[$key]['time'] = $value->time;
							$items = Excerciseplandetails::find()->where(['explanId'=>$value->explanId])->all();							
							$data[$key]['items'] = $items;
						}
					}
					$previous_weekplans = Excerciseplans::find()->where(['userId'=>$get['userId'],'createdDate'=>$start_week,'updatedDate'=>$end_week])->all();
					$previous = [];
					if(!empty($previous_weekplans))
					{
						foreach($previous_weekplans as $x=>$y)
						{
							$previous[$x]['time'] = $y->time;
							$items = Excerciseplandetails::find()->where(['explanId'=>$y->explanId])->all();
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
	
	public function actionUserExcerciseTrack()
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
			$values =  Userexcercisetrack::find()->select('createdDate')->where(['access_token' => $access_token])->orderBy([
            'createdDate' => SORT_DESC])->distinct()->asArray()->all();	
			foreach($values as $key=>$value)
			{
				$data = Userexcercisetrack::find()->where(['createdDate' => $value['createdDate']])->asArray()->all();
				$newdata[$key]['date'] = $value['createdDate'];
				$newdata[$key]['data'] = $data;
			}
			return ['status' => true, 'message' => 'Success','data'=>$newdata];
	   }		
	}
	
	
	public static function actionAddDoctorprescription()
	{
		$data = array();
		$post = Yii::$app->request->post();
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
				    $d = date('Y-m-d');
					$access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;
					$Prescription = Prescription::find()->where(['access_token' => $access_token,'createdBy'=>$user->id,'createdDate'=>$d])->one();
					if(!empty($Prescription))
					{
						DoctorTests::deleteAll(['prescriptionId' => $Prescription->prescriptionId]);
						Medicines::deleteAll(['prescriptionId' => $Prescription->prescriptionId]);
					}
					Prescription::deleteAll(['access_token' => $access_token,'createdBy'=>$user->id,'createdDate'=>$d]);
					$model = new Prescription();
					$model->diagnosticCenter = $post['diagnosticCenter'];
					$model->access_token = $access_token;
					$model->type = $post['type'];
					$model->createdBy = $user->id;
					$model->updatedBy = $user->id;
					$model->createdDate = $d;
					$model->updatedDate = $d;
					$model->ipAddress = $d;
					if(!($model->save())){
					print_r($model->errors);exit;	
					}
					foreach($post['tests'] as $key=>$value)
					{
							$testmodel = new DoctorTests();
							$testmodel->prescriptionId = $model->prescriptionId;
							$testmodel->testname = $value['itemId'].','.$value['itemName'];
							$testmodel->save();				
					}
					
					foreach($post['data'] as $k=>$v)
					{
							$medicinemodel = new Medicines();
							$medicinemodel->prescriptionId = $model->prescriptionId;
							$medicinemodel->medicineMId = $v['medicine']['medicineId'];
							$medicinemodel->medicineName = $v['medicine']['medicineName'];
							foreach($v['usage'] as $uk=>$uv)
							{
								if(empty($medicinemodel->usageId ))
								{
									$medicinemodel->usageId = $uv['usageId'];
									$medicinemodel->usageName = $uv['usageName'];
								}
								else
								{
									$medicinemodel->usageId = $medicinemodel->usageId.','.$uv['usageId'];
									$medicinemodel->usageName = $medicinemodel->usageName.','.$uv['usageName'];
								}
							}
							$medicinemodel->durationId = $v['duration']['durationId'];
							$medicinemodel->durationName = $v['duration']['name'];
							if(!$medicinemodel->save()){
								print_r($medicinemodel->errors);exit;
							}
									
					}
					$date = date('Y-m-d');
		
		$previous_week = strtotime("-1 week +1 day");
		$d = date("l");
		$start_week = strtotime("last ".$d." midnight",$previous_week);				
		$start_week = date("Y-m-d",$start_week);
		$end_week = date("Y-m-d");
		$doctor = Doctors::find()->where(['userId'=>$user->id])->one();
		
		$patient = Userprofile::find()->where(['userId'=>$_GET['patId']])->one();
		$access_token  = User::find()->where(['id'=>$_GET['patId']])->one()->access_token;
		$weightavg = number_format(Userprofile::find()->where(['access_token'=>$access_token])->average('weight'),1);
		$bmi = number_format(Bmivalues::find()->where(['access_token'=>$access_token])->average('BMI'),1);
		
		$height = Userprofile::find()->where(['access_token'=>$access_token])->one();
		//print_r($height->height);exit;
		$heightweightavg = $height->height.' CM / '.$weightavg .'Kgs';
		$avgbp = ceil(Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('SystolicValue')).'/'.ceil(Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('DiastolicValue'));
		$averageglucose = ceil(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
		$averagemeal = number_format(Usermeals::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->sum('cal'),2);
		$sym = Patientsymptoms::find()->select(['createdDate'])->orderBy('symId DESC')->one();
		//print_r($sym);exit;
		$symmodel = Patientsymptoms::find()->where(['createdDate'=>$sym->createdDate])->asArray()->all();
		$symdata = [];
		foreach($symmodel as $key=>$value)
		{
			$symdata[$key] = $value['text'];
	    } 
		$symdata = implode(',',$symdata);
		$pre = Prescription::find()->orderBy('prescriptionId DESC')->one();
		$test = DoctorTests::find()->where(['prescriptionId'=>$pre->prescriptionId])->all();
		$textdata = [];
		foreach($test as $tkey=>$tvalue)
		{
			$testname = explode(',',$tvalue['testname']);
			$textdata[$tkey] = $testname['1'];
		}
		$textdata = implode(',',$textdata);
		$medicines = Medicines::find()->where(['prescriptionId'=>$pre->prescriptionId])->all();
		$tabledata ='';
		foreach($medicines as $mkey=>$mvalue)
		{
			$tabledata .= "<tr>";
			$tabledata .="<td>".$mvalue['medicineName']."</td>";
			$tabledata .="<td>".$mvalue['usageName']."</td>";
			$tabledata .="<td>".$mvalue['durationName']."</td>";
			$tabledata .="</tr>";
		}
		$remark = Remarks::find()->select(['createdDate'])->orderBy('remarkId DESC')->one();
		$remarkmodel = Remarks::find()->where(['createdDate'=>$remark->createdDate])->asArray()->all();
		$remarkdata = [];
		foreach($remarkmodel as $rkey=>$rvalue)
		{
			$remarkdata[$rkey] = $rvalue['text'];
	    } 
		$remarkdata = implode(',',$remarkdata);
		$html = <<<HTML
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Apollo Sugar Doctor Prescription</title>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
td
{
	padding:5px;
}
</style>
</head>

<body style="box-sizing:border-box; font-family: 'Source Sans Pro',sans-serif; margin:0px; padding:0px;">
<div style="width:576px;  height:auto; overflow:hidden; margin:10px auto; border:1px solid #bce8f1; border-radius:4px; padding:10px; position:relative;">
  <header style="width:576px; float:left; position: relative; border-bottom: 2px solid #5aab4a; padding: 0 10px; box-sizing: border-box;">
    <h1 style="font-family: 'Source Sans Pro',sans-serif; font-size: 38px; color:#ff6600; text-align:center; margin:0px;"><img src="https://apollosugar.com/wp-content/uploads/2020/09/Apollo-Sugar-New-Logo-1-e1598960920160.jpg"></img></h1>
    <h3 style="font-family: 'Source Sans Pro',sans-serif; color: #323232; font-size: 18px; text-align:center; margin:0px;">Doctor Prescription</h3>
    <div style="width:556px; float:left; position: relative;">
      <div style="width:278px; float:left; position:relative;">
        <div style="width: 278px; float: left; position: relative; padding:0 0 10px 0;">
          <label style="width: 139px; float: left; color: #295a8c; font-size: 14px;">DR NAME <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
          <span style="font-size: 14px; color: #333; max-width: 139px; float: left;">Dr. $user->username</span> </div>
        
        <div style="width: 278px; float: left; position: relative; padding:0 0 10px 0;">
          <label style="width: 139px; float: left; color: #295a8c; font-size: 14px;">QUALIFICATION <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
          <span style="font-size: 14px; color: #333; max-width: 139px; float: left;">$doctor->qualification</span> </div>
      </div>
      <div style="width:278px; float:left; position:relative; margin-top:20px;">
        <h3 style="font-size: 15px; color: #333; float: right; padding:12px 0; text-align: right; margin:0px;">Date: <span>$date</span></h3>
      </div>
    </div>
  </header>
  <aside style="width:576px; float:left; position: relative; border-bottom: 2px solid #5aab4a; padding:10px; padding-bottom:0px; box-sizing: border-box;">
    <div style="width:270px; float:left; position:relative; padding-right:5px; box-sizing:border-box;">
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">PATIENT NAME <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$patient->firstName $patient->lastName</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">AGE /SEX <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$patient->age / $patient->gender</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Symptoms <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$symdata</span> </div>
        <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Tests Recommended <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$textdata</span> </div>
      
      
    </div>
    <div style="width:270px; float:left; position:relative; padding-left:5px; box-sizing:border-box;">
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">PATIENT UHID <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$patient->userId</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">BP <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$avgbp mmHg</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Height/weight <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$heightweightavg</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">BMI <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$bmi kg/m2</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Avg Glucose <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$averageglucose mg/dL</span> </div>
        
        <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Avg Calories Intake /Day <i style="font-style: bold; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$averagemeal Kcal</span> </div>
      
    </div>
    <div style="width:556px; float:left; position:relative;">
      <h6 style="color:#3572af; font-weight:600; font-size:14px;  margin:0px; padding:10px;">Medicine / Insulin</h6>
      <table style="height:200px; width:100%; float:left; position:relative; font-size:14px; margin:0 0 10px 0; padding:10px; box-sizing:border-box; border:1px solid black; border-radius:5px;"> 
			<tr>
				<th>Medicine Name</th>
				<th>Usage</th>
				<th>Duration</th>
			</tr>
			$tabledata
	  </table>
    </div>
	<div style="width:556px; float:left; position:relative;">
      <h6 style="color:#3572af; font-weight:600; font-size:14px;  margin:0px; padding:10px;">Remarks</h6>
      <div style="height:200px; width:100%; float:left; position:relative; font-size:14px; margin:0 0 10px 0; padding:10px; box-sizing:border-box; border:1px solid #999; border-radius:5px;"> $remarkdata </div>
    </div>
  </aside>
  <footer style="width:556px; float:left; position: relative; padding:10px; padding-bottom:0px; box-sizing: border-box;">
    <div style="width: 556px; float: left; position: relative; padding:0px; font-family: 'Source Sans Pro',sans-serif; font-size: 13px; color: #989898; text-align:center;"> Apollo Sugar </div>
  </footer>
</div>
</body>
</html> 	
    	
    	
    	
   
HTML;
    //print_r("frontend/web/Pdfs/".date('Y-m-d').'-'.$model->prescriptionId."Prescription.pdf");exit;
    	$pdf = Yii::$app->pdf;
     	$mpdf = $pdf->api;
        $mpdf->WriteHTML($html); //pdf is a name of view file responsible for this pdf document
		$path = $mpdf->Output(Yii::getAlias("@frontend")."/web/Pdfs/".$model->prescriptionId.'-'.date('Y-m-d')."Prescription.pdf", "F");
		$pdfs = new Prescriptionpdfs();
		$pdfs->prescriptionId = $model->prescriptionId;
		$pdfs->fileName = "frontend/web/Pdfs/".$model->prescriptionId.'-'.date('Y-m-d')."Prescription.pdf";
		$pdfs->createdDate = date('Y-m-d');
		$pdfs->createdBy = $user->id;
		$pdfs->access_token = $access_token;
		$pdfs->save();
					return ['status' => true, 'message' => 'Success'];
			}		
	}
	public function actionPdf()
	{
		$date = date('Y-m-d');
		$user = User::find()
            ->where(['access_token' => "CYj9s1Gx7WZTE3NJsFB_tXaqVb_Kb-BZsLgUu-4s"])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
		$previous_week = strtotime("-1 week +1 day");
		$d = date("l");
		$start_week = strtotime("last ".$d." midnight",$previous_week);				
		$start_week = date("Y-m-d",$start_week);
		$end_week = date("Y-m-d");
		$doctor = Doctors::find()->where(['userId'=>$user->id])->one();
		$patient = Userprofile::find()->where(['userId'=>31])->one();
		$access_token  = User::find()->where(['id'=>31])->one()->access_token;
		$weightavg = number_format(Userprofile::find()->where(['access_token'=>$access_token])->average('weight'),1);
		$bmi = number_format(Bmivalues::find()->where(['access_token'=>$access_token])->average('BMI'),1);
		$height = Userprofile::find()->where(['access_token'=>$access_token])->one()->height;
		$heightweightavg = $height.' Feet / '.$weightavg .'Kgs';
		$avgbp = ceil(Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('SystolicValue')).'/'.ceil(Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('DiastolicValue'));
		$averageglucose = ceil(Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->average('glucosevalue'));
		$averagemeal = number_format(Usermeals::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->sum('cal'),2);
		$sym = Patientsymptoms::find()->select(['createdDate'])->orderBy('symId DESC')->one();
		$symmodel = Patientsymptoms::find()->where(['createdDate'=>$sym->createdDate])->asArray()->all();
		$symdata = [];
		foreach($symmodel as $key=>$value)
		{
			$symdata[$key] = $value['text'];
	    } 
		$symdata = implode(',',$symdata);
		$pre = Prescription::find()->orderBy('prescriptionId DESC')->one();
		$test = DoctorTests::find()->where(['prescriptionId'=>$pre->prescriptionId])->all();
		$textdata = [];
		foreach($test as $tkey=>$tvalue)
		{
			$testname = explode(',',$tvalue['testname']);
			$textdata[$tkey] = $testname['1'];
		}
		$textdata = implode(',',$textdata);
		$medicines = Medicines::find()->where(['prescriptionId'=>$pre->prescriptionId])->all();
		$tabledata ='';
		foreach($medicines as $mkey=>$mvalue)
		{
			$tabledata .= "<tr>";
			$tabledata .="<td>".$mvalue['medicineName']."</td>";
			$tabledata .="<td>".$mvalue['usageName']."</td>";
			$tabledata .="<td>".$mvalue['durationName']."</td>";
			$tabledata .="</tr>";
		}
		$remark = Remarks::find()->select(['createdDate'])->orderBy('remarkId DESC')->one();
		$remarkmodel = Remarks::find()->where(['createdDate'=>$remark->createdDate])->asArray()->all();
		$remarkdata = [];
		foreach($remarkmodel as $rkey=>$rvalue)
		{
			$remarkdata[$rkey] = $rvalue['text'];
	    } 
		$remarkdata = implode(',',$remarkdata);
		$html = <<<HTML
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Apollo Sugar Doctor Prescription</title>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
td
{
	padding:5px;
}
</style>
</head>

<body style="box-sizing:border-box; font-family: 'Source Sans Pro',sans-serif; margin:0px; padding:0px;">
<div style="width:576px;  height:auto; overflow:hidden; margin:10px auto; border:1px solid #bce8f1; border-radius:4px; padding:10px; position:relative;">
  <header style="width:576px; float:left; position: relative; border-bottom: 2px solid #5aab4a; padding: 0 10px; box-sizing: border-box;">
    <h1 style="font-family: 'Source Sans Pro',sans-serif; font-size: 38px; color:#ff6600; text-align:center; margin:0px;"><img src="https://apollosugar.com/wp-content/uploads/2020/09/Apollo-Sugar-New-Logo-1-e1598960920160.jpg"></img></h1>
    <h3 style="font-family: 'Source Sans Pro',sans-serif; color: #323232; font-size: 18px; text-align:center; margin:0px;">Doctor Prescription</h3>
    <div style="width:556px; float:left; position: relative;">
      <div style="width:278px; float:left; position:relative;">
        <div style="width: 278px; float: left; position: relative; padding:0 0 10px 0;">
          <label style="width: 139px; float: left; color: #295a8c; font-size: 14px;">DR NAME <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
          <span style="font-size: 14px; color: #333; max-width: 139px; float: left;">Dr. $user->username</span> </div>
        
        <div style="width: 278px; float: left; position: relative; padding:0 0 10px 0;">
          <label style="width: 139px; float: left; color: #295a8c; font-size: 14px;">QUALIFICATION <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
          <span style="font-size: 14px; color: #333; max-width: 139px; float: left;">$doctor->qualification</span> </div>
      </div>
      <div style="width:278px; float:left; position:relative; margin-top:20px;">
        <h3 style="font-size: 15px; color: #333; float: right; padding:12px 0; text-align: right; margin:0px;">Date: <span>$date</span></h3>
      </div>
    </div>
  </header>
  <aside style="width:576px; float:left; position: relative; border-bottom: 2px solid #5aab4a; padding:10px; padding-bottom:0px; box-sizing: border-box;">
    <div style="width:270px; float:left; position:relative; padding-right:5px; box-sizing:border-box;">
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">PATIENT NAME <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$patient->firstName $patient->lastName</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">AGE /SEX <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$patient->age / $patient->gender</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Symptoms <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$symdata</span> </div>
        <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Tests Recommended <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$textdata</span> </div>
      
      
    </div>
    <div style="width:270px; float:left; position:relative; padding-left:5px; box-sizing:border-box;">
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">PATIENT UHID <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$patient->userId</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">BP <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$avgbp mmHg</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Height/weight <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$heightweightavg</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">BMI <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$bmi kg/m2</span> </div>
      <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Avg Glucose <i style="font-style: normal; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$averageglucose mg/dL</span> </div>
        
        <div style="width: 270px; float: left; position: relative; padding:0 0 10px 0;">
        <label style="width: 130px; float: left; color: #295a8c; font-size: 14px;">Avg Calories Intake /Day <i style="font-style: bold; float: right; padding-right: 15px;">:</i></label>
        <span style="font-size: 14px; color: #333; max-width: 130px; float: left;">$averagemeal Kcal</span> </div>
      
    </div>
    <div style="width:556px; float:left; position:relative;">
      <h6 style="color:#3572af; font-weight:600; font-size:14px;  margin:0px; padding:10px;">Medicine / Insulin</h6>
      <table style="height:200px; width:100%; float:left; position:relative; font-size:14px; margin:0 0 10px 0; padding:10px; box-sizing:border-box; border:1px solid black; border-radius:5px;"> 
			<tr>
				<th>Medicine Name</th>
				<th>Usage</th>
				<th>Duration</th>
			</tr>
			$tabledata
	  </table>
    </div>
	<div style="width:556px; float:left; position:relative;">
      <h6 style="color:#3572af; font-weight:600; font-size:14px;  margin:0px; padding:10px;">Remarks</h6>
      <div style="height:200px; width:100%; float:left; position:relative; font-size:14px; margin:0 0 10px 0; padding:10px; box-sizing:border-box; border:1px solid #999; border-radius:5px;"> $remarkdata </div>
    </div>
  </aside>
  <footer style="width:556px; float:left; position: relative; padding:10px; padding-bottom:0px; box-sizing: border-box;">
    <div style="width: 556px; float: left; position: relative; padding:0px; font-family: 'Source Sans Pro',sans-serif; font-size: 13px; color: #989898; text-align:center;"> Apollo Sugar </div>
  </footer>
</div>
</body>
</html> 	
    	
    	
    	
   
HTML;
    
    	$pdf = Yii::$app->pdf;
     	$mpdf = $pdf->api;
        $mpdf->WriteHTML($html); //pdf is a name of view file responsible for this pdf document
		$path = $mpdf->Output(Yii::getAlias("@frontend")."/web/Pdfs/".date('Y-m-d')."Prescription.pdf", "F");
		$pdfs = new Prescriptionpdfs();
		$pdfs->prescriptionId = 8;
		$pdfs->fileName = "frontend/web/Pdfs/".date('Y-m-d')."Prescription.pdf";
		$pdfs->createdDate = date('Y-m-d');
		$pdfs->createdBy = $user->id;
		$pdfs->access_token = $access_token;
		$pdfs->save();
		//return $pdf->render();
	}
   
	public function actionRoastertimings()
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
		    
			return ['status' => true, 'message' => 'Success','data'=>$newdata];
	   }		
	}
	
	public function actionPrescriptionpdfs()
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
		    $access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;					
		    $model = Prescription::find()->where(['access_token'=>$access_token,'createdBy'=>$user->id])->orderBy('createdDate DESC')->asArray()->all();
			$newdata = [];
			foreach($model as $key=>$value)
			{
				$pdflinks = Prescriptionpdfs::find()->where(['prescriptionId'=>$value['prescriptionId']])->one();
				$pdflink = Yii::$app->request->hostInfo."/ApolloSugar/".$pdflinks->fileName;
				$value['fileName'] = $pdflink;
				$newdata[$key] = $value;				
			}
			return ['status' => true, 'message' => 'Success','data'=>$newdata];
	   }		
	}
	public function actionLatestprescriptionpdf()
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
		    $access_token = User::find()->where(['id' => $_GET['patId']])->one()->access_token;					
		    $value = Prescription::find()->where(['access_token'=>$access_token,'createdBy'=>$user->id])->orderBy('createdDate DESC')->asArray()->one();
			$newdata = [];			
			$pdflinks = Prescriptionpdfs::find()->where(['prescriptionId'=>$value['prescriptionId']])->one();
			$pdflink = "https://devapp.apollohl.in:8443/ApolloSugar/".$pdflinks->fileName;
			$value['fileName'] = $pdflink;
			$newdata[0] = $value;	
			return ['status' => true, 'message' => 'Success','data'=>$newdata];
	   }		
	}
	
	public static function actionPlans()
    {
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
                $model = Plans::find()->where(['doctorId'=>$user->id,'doctordriven'=>1])->asArray()->all();
				$data = [];
				if(empty($model))
				{
					$model = Plans::find()->where(['doctordriven'=>0])->asArray()->all();
				}
				else
				{
					$general = Plans::find()->where(['doctordriven'=>0,'general'=>1])->asArray()->all();
					$model = array_merge($model,$general);
				}
				foreach($model as $key=>$value)
				{
						$value['Price'] = intval($value['Price']);
						$value['offerPrice'] = intval($value['offerPrice']);
						$diagnosticstests = [];
						
						$diagnosticamount = 0;
						if($user->roleId == 8)
						{
							$textarray[0] =1; 
							$textarray[1] = 2;
							$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['NOT IN','text',$textarray])->andwhere(['planId'=>$value['planId']])->distinct()->asArray()->all();
							if($upcomingdetailsarraydata != [])
							{
								foreach($upcomingdetailsarraydata  as $k=>$v)
								{
									$amount = 0;
									$diagnosticstests[$k]['day'] = $v['day'];
									$diagnosticstests[$k]['endday'] = $v['endday'];
									$items = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$v['day'],'endday'=>$v['endday'],'test_type'=>'pathtests','planId'=>$value['planId']])->asArray()->all();
									if($items !=[]){
										foreach($items as $ik=>$iv)
										{
											$diagnosticstests[$k]['tests'][] = $iv['itemName'];
											$diagnosticamount = $diagnosticamount+$iv['rate'];
											$amount = $amount + $iv['rate'];
											
										}
									}
									else
									{
										$diagnosticstests[$k]['tests'] = [];
									}
									$diagnosticstests[$k]['amount'] = $amount;
								}
							}
							
						}
						$value['diagnosticamount'] = $diagnosticamount-($diagnosticamount*($value['discount']/100));
						$value['diagnosticstests'] = $diagnosticstests;
						$data[] = $value;
				}				
                return ['status' => true, 'message' => 'Plans Data', 'data' => $data]; 
            }  
    }
	public function actionPlandetails()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Planinclusions();
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
                $model = Planinclusions::find()->where(['planId'=>$_GET['id']])->orderBy('planIncId DESC')->all();
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
				$consultations =[];
                $details = Plandetails::find()->select('day, endday')->where(['planId'=>$_GET['id']])->distinct()->asArray()->all();
				if($details != [])
				{
					foreach($details as $x=>$v)
					{
								$ditems = [];
								$consultations[$x]['day'] =  $v['day'];
								$consultations[$x]['endday'] = $v['endday'];
								$details = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$_GET['id']])->asArray()->all();
								if($details != []){
									foreach($details as $dk=>$dv)
									{
										$ditems[] = $dv['itemName'];
									}	
								}
				                $consultations[$x]['text'] = $ditems;									
					}							
				}
                return ['status' => true, 'message' => 'Plan', 'data' => $data,'consultations'=>$consultations]; 
            } 
	}
	
	public function actionSharewhatsapp()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Planinclusions();
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
			  $planname = Plans::find()->where(['planId'=>$post['planId']])->one()->PlanName;
			  $message = "Recommended Sugar Program is ".$planname." If you don't Have Apollo Sugar App Please Install And Subscribe Recommended Plan";
              $otpmessage = "Dear Customer, You are subscribed to ".$planname.". For your hassle free diabetic journey, download the app ".$planname.". Thanks, Apollo Sugar Clinics";
			  $smsurl = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$post['mobilenumber'].'&message='.urlencode($otpmessage).'&msgtype=TXT&response=Y';
           	  //print_r($smsurl);exit;
			  $crl = curl_init();
			  curl_setopt($crl, CURLOPT_URL, $smsurl);
			  curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
			  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
              $res = curl_exec($crl);
			  $url = 'https://api.whatsapp.com/send?phone='.$post['mobilenumber'].'&text='. urlencode($message);
           	  $model =  Doctordrivenlinks::find()->where(['doctorId'=>$user->id,'mobilenumber'=>$post['mobilenumber'],'programId'=>$post['planId']])->one();
			  $fcm_id = "eKqEUXZgRIKxbWt40bSEn5:APA91bH_zNbxkLn-epujYyHuQ39JLV6nwkkuem9jWQtOJS7w190xI7B2EoMgedr30KyBFOSdp30igKlwI_COa2o6v2xbCipknQ70GBK8ncgJz4HaCFWNJgdIWBI-mH0Ksr5_XAqhYZU0";
			  //$result = Notifications::addUserFcm($user->id, $fcm_id);
			  if(empty($model))
			  {
						$model = new Doctordrivenlinks();
						$model->doctorId = $user->id;
					    $model->mobilenumber = $post['mobilenumber'];
						$model->name = $post['name'];
						$model->programId = $post['planId'];
						$model->status = 'Success';
						$model->save();
				}
                return ['status' => true, 'message' => 'Success','link'=>$url]; 
            } 
	}

    public function actionPlanpatients()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Planinclusions();
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
				$links = Doctordrivenlinks::find()->where(['programId'=>$_GET['id'],'doctorId'=>$user->id])->all();
			    return ['status' => true, 'message' => 'Plan', 'data' => $links]; 
            } 
	}
	
	public static function actionDoctorsession()
    {
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
                $session  = Doctorslotsession::find()->Where(['bookingId'=>$_GET['bookingId']])->one();
				$booking = Slotbooking::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				$mobile  = User::find()->where(['access_token'=>$booking->access_token])->one();
				if(empty($session))
				{
					$session = new Doctorslotsession();
					$session->bookingId = $_GET['bookingId'];
					$session->slotDate = $booking->slotDate;
					$session->createdDate = date('Y-m-d');
					$session->updatedDate = date('Y-m-d');
					$session->session = Yii::$app->getSecurity()->generateRandomString(40);
					$session->createdBy = $user->id;
					$session->status = 'Started';
					$session->save();

					$message = $booking->videolink.'/'.$mobile->username;
					$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$mobile->username.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
					$crl = curl_init();
					curl_setopt($crl, CURLOPT_URL, $url);
					curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
					curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
					$res = curl_exec($crl);
				}

				else
				{
					$message = $booking->videolink.'/'.$mobile->username;
					$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$mobile->username.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
					$crl = curl_init();
					curl_setopt($crl, CURLOPT_URL, $url);
					curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
					curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
					$res = curl_exec($crl);
					return ['status' => true, 'message' => 'Session Already Started','session'=>$session->session,'link'=>$booking->videolink];
				}
				
				return ['status' => true, 'message' => 'Session Started','session'=>$session->session,'link'=>$booking->videolink];
            }  
    }
	public static function actionDieticianslotsession()
    {
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
                $session  = Dieticianslotsession::find()->Where(['bookingId'=>$_GET['bookingId']])->one();
				$booking = Dieticianslotbooking::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				$mobile  = User::find()->where(['access_token'=>$booking->access_token])->one();
				if(empty($session))
				{
					$session = new Dieticianslotsession();
					$session->bookingId = $_GET['bookingId'];
					$session->slotDate = $booking->slotDate;
					$session->createdDate = date('Y-m-d');
					$session->updatedDate = date('Y-m-d');
					$session->session = Yii::$app->getSecurity()->generateRandomString(40);
					$session->createdBy = $user->id;
					$session->status = 'Started';
					$session->save();


					$message = $booking->videolink.'/'.$mobile->username;
					$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$mobile->username.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
					$crl = curl_init();
					curl_setopt($crl, CURLOPT_URL, $url);
					curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
					curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
					$res = curl_exec($crl);
				}
				else
				{
					$message = $booking->videolink.'/'.$mobile->username;
					$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$mobile->username.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
					$crl = curl_init();
					curl_setopt($crl, CURLOPT_URL, $url);
					curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
					curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
					$res = curl_exec($crl);
					
					return ['status' => true, 'message' => 'Session Already Started','session'=>$session->session,'link'=>$booking->videolink];
				}
				
				return ['status' => true, 'message' => 'Session Started','session'=>$session->session,'link'=>$booking->videolink];
            }  
    }
	public function actionUpdatevisitid()	{
		$data = array();
		$post = Yii::$app->request->post();
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
			$model = Orders::find()->where(['orderId'=>$post['bookingId']])->one();
			$model->bookingStatus = "Reports Generated";
			$model->visitId = $post['visitId'];
			$model->updatedDate = date('Y-m-d'); 
			$model->updatedBy = $user->id; 
			$model->save();
			return ['status' => true, 'message' => 'Success'];
	   }		
	}	
	
	public function actionDashboardbkp()	{
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
			$pending = 0;
			$Realiticamount = 0;
			$potentialrevenue = 0;
			if($user->roleId == 8)
			{
				$userplans = userplans::find()->where(['clinicId'=>$user->id,'Status'=>'Subcribed'])->orderBy('userPlanId DESC')->asArray()->all();
				$expired = userplans::find()->where(['clinicId'=>$user->id,'Status'=>'Expired'])->count();
			}
			if($userplans != [])
			{
				foreach($userplans  as $k=>$v)
				{
					$data = $this->revenuce($v);
					$potentialrevenue = $potentialrevenue + $data['diagnosticamount'];
					$Realiticamount = $Realiticamount + $data['realisticamount'];
				}
			}
			$curl = curl_init();
			$data = array();
			$user = User::find()->where(['id'=>$user->id])->one();
			$url = Yii::$app->request->hostInfo.'/SugarFranchies/frontend/web/index.php?r=site/referrallist';
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $user->access_token
			));
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$output = json_decode(curl_exec($curl),true);
			//print_r($output);exit;
			$newdata = $output['data'];
			
			curl_close($curl);	
			
			$femalecount = 0;
			$malecount = 0;
			if($newdata != []){
			foreach($newdata as $key=>$value)
			{
				$data[$key]['name'] = $value['name'];			
				$data[$key]['age'] = $value['age'];
				$data[$key]['mobilenumber']=$value['mobilenumber'];
				$data[$key]['gender'] = $value['gender'];
				if($value['referedstatus'] == 'Pending')
				{
					$pending = $pending + 1;					
				}
				$data[$key]['status'] = $value['referedstatus'];
			}
			}
			$count[0]['title'] = 'Refered Patients';
			$count[0]['value'] = count($newdata);
			$count[1]['title'] = 'Converted';
			$count[1]['value'] = count($userplans);
			$count[2]['title'] = 'Non-Converted';
			$count[2]['value'] = $pending;
			$count[3]['title'] = 'Expired';
			$count[3]['value'] = $expired;
			$piechart[0]['name']="Potential Revenuve";
			$piechart[0]['value']= $potentialrevenue;
			$piechart[1]['name']= "Realitic Revenuve";
			$piechart[1]['value']= $Realiticamount;
			
			 return ['status' => true, 'message' => 'Success','count'=>$count,'piechart'=>$piechart,'totalpatients'=>$data,
			];
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
			$newdata = [];
			$piechart = [];
			$expired = 0;
			$pending = 0;
			$Realiticamount = 0;
			$potentialrevenue = 0;
			if($user->roleId == 8)
			{
				$userplans = userplans::find()->where(['clinicId'=>$user->id,'Status'=>'Subcribed'])->orderBy('userPlanId DESC')->asArray()->all();
				$expired = userplans::find()->where(['clinicId'=>$user->id,'Status'=>'Expired'])->count();
				$totalplans = userplans::find()->where(['clinicId'=>$user->id])->andwhere(['!=','Status','Expired'])->orderBy('userPlanId DESC')->asArray()->all();
			}
			if($userplans != [])
			{
				foreach($userplans  as $k=>$v)
				{
					$data = $this->revenuce($v);
					$potentialrevenue = $potentialrevenue + $data['diagnosticamount'];
					$Realiticamount = $Realiticamount + $data['realisticamount'];
				}
			}
			
			foreach($totalplans as $key=>$value)
			{				
				$totalpatientsarray[$key] = $this->view($value['access_token']);
				if($totalpatientsarray[$key]['subscriptionstatus'] == 'Un-Subcribed'){
					$pending = $pending +1;
				}
			}
			$count[0]['title'] = 'Referred Patients';
			$count[0]['value'] = count($totalpatientsarray);
			$count[1]['title'] = 'Converted';
			$count[1]['value'] = count($userplans);
			$count[2]['title'] = 'Non-Converted';
			$count[2]['value'] = count($totalpatientsarray) - count($userplans);
			$count[3]['title'] = 'Subscription Expired';
			$count[3]['value'] = $expired;
			$piechart[0]['name']="Potential Revenuve";
			$piechart[0]['value']= $potentialrevenue;
			$piechart[1]['name']= "Realised Revenuve";
			$piechart[1]['value']= $Realiticamount;			
			return ['status' => true, 'message' => 'Success','count'=>$count,'piechart'=>$piechart,'totalpatients'=>$totalpatientsarray,
		 ];
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
		$plan = Plans::find()->where(['planId'=>$subscriptionstatus->planId])->one();
		$data['programname'] = $plan->PlanName;
		if($subscriptionstatus)
		{
			if($subscriptionstatus->Status == 'Subcribed')
			{
				$data['subscriptionstatus'] = 'Subscribed';
			}
			else
			{
				$data['subscriptionstatus'] = 'Un-Subscribe';
			}
			$data['orderId'] = $subscriptionstatus->userPlanId;			
		}
		else
		{
			$data['subscriptionstatus'] = "";
			$data['orderId'] = 0;
		}
		return $data;		
	}
	
	public function revenuce($newplan)	{
		$data = [];
		$plan = Plans::find()->where(['planId'=>$newplan['planId']])->one();
		$textarray[0] =1; 
	    $textarray[1] = 2;
		$diagnosticamount = 0;
		$realisticamount = 0;
		$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['NOT IN','text',$textarray])->andwhere(['planId'=>$plan['planId']])->distinct()->asArray()->all();
		if($upcomingdetailsarraydata != [])
		{
			foreach($upcomingdetailsarraydata as $key=>$value)
			{
				
				$newitems = [];
				$items = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$value['day'],'endday'=>$value['endday'],'test_type'=>'pathtests','planId'=>$plan['planId']])->asArray()->all();
				if($items !=[])
				{
					foreach($items as $ik=>$iv)
					{
						
						$diagnosticamount = $diagnosticamount+$iv['rate'];
						$newitems[] = $iv['itemId'];						
					}					
				}				
			}
		}
		$query = Orders::find()->where(['orders.access_token'=>$newplan['access_token']])->andwhere(['>=','slotDate',$newplan['createdDate']])->andwhere(['!=','orders.visitId','NULL'])->orderBy('orders.orderId DESC')->all();
		if(!empty($query))
		{		
			foreach($query as $x=>$v)
			{
				$amount =0; 
				$orderitems = Orderitems::find()->where(['orderId'=>$v->orderId])->all();
				foreach($orderitems as $px=>$pk){
					$amount = $amount + $pk->price;
				}			
				$realisticamount = $realisticamount+$amount;
			}
		}
		$data['planId'] = $plan['planId'];
		$data['diagnosticamount'] = $diagnosticamount - ($diagnosticamount*($plan['discount']/100));
		$data['realisticamount'] = $realisticamount;
		//print_r($data);
		return $data;		
	}
}
