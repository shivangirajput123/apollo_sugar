<?php 
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use backend\models\Appointments;
use backend\models\Appointmentrecords;
use backend\modules\packages\models\Plans;
use backend\modules\packages\models\Packages;
use backend\modules\packages\models\Packageitems;
use backend\modules\packages\models\ItemDetails;
use backend\modules\packages\models\Planinclusions;
use common\models\User;
use backend\models\Diettrack;
use backend\modules\common\models\Mealtype;
use backend\modules\common\models\Fooditems;
use backend\modules\common\models\Fooditemdetails;
use frontend\models\Userprofile;
use frontend\models\Orders;
use frontend\models\Orderitems;
use backend\modules\packages\models\Plansuggestions;
use backend\modules\packages\models\Plandetails;
use backend\modules\packages\models\Doctordrivenlinks;
use frontend\models\Userplans;
class AppointmentsController extends Controller
{
    public static function actionSave()
    {
        $data = array(); 
        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');		
        $post = Yii::$app->request->post();
		if (empty($post['doctorId'])) 
            {
                return ['status' => false, 'message' => 'doctorId is required'];
            }
			if (empty($post['patId'])) 
            {
                return ['status' => false, 'message' => 'Patient Id is required'];
            }
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
				
				if(empty($post['apId']))
				{
					
					$model = new Appointments();
					$model->doctorId = $post['doctorId'];
					$model->patId = $post['patId'];
					$model->createdDate = date('Y-m-d');
					$model->updatedDate = date('Y-m-d');
					$model->save();
					$post['apId'] = $model->apId;
				}
				else
				{
					$appointment = Appointments::find()->where(['apId'=>$post['apId']])->one();
					$appointment->updatedDate = date('Y-m-d');
					$appointment->save();
				}
				$records = new Appointmentrecords();
				$records->apId = $post['apId'];
				$records->bp = $post['bp'];
				$records->sugar = $post['sugar'];
				$records->weight = $post['weight'];
				$records->postprandial = $post['postprandial'];
				$records->HbA1c = $post['HbA1c'];
				$records->creatinine = $post['creatinine'];
				$records->BMI = $post['BMI'];
				$records->fromdevice = $post['fromdevice'];
				$records->status = $post['status'];
				$records->createdDate = date('Y-m-d');
				$records->updatedDate = date('Y-m-d');
				$records->save();
				return ['status' => true, 'message' => 'Success'];
			}
    }	
	public function actionPlansnew()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Plans();
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
				$profile = Userprofile::find()->where(['userId'=>$user->id])->one();
				$sug = Plansuggestions::find()->all();
				$model = [];
				foreach($sug as $key=>$value)
				{						
					$plan = Plans::find()->where(['planId'=>$value->planId])->one();
					$model[] = $plan;
				}
                return ['status' => true, 'message' => 'Plans', 'data' => $model]; 
            }  
	}	
	public function actionPlansbkp()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Plans();
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
				$model = [];
				$profile = Userprofile::find()->where(['userId'=>$user->id])->one();
				$doctorplanId = Doctordrivenlinks::find()->where(['mobilenumber'=>$user->username,'status'=>'Success'])->all();
				
				if(empty($doctorplanId)){
				//print_r($profile);exit;
				$programssql = "select * FROM plansuggestions INNER JOIN plans ON plans.planId = plansuggestions.planId where doctordriven=0";
				if(!empty($profile))
				{
					if($profile->gender == 'Male')
					{
						$programssql .= "  AND gender in ('Male','Both')";
					}
					elseif($profile->gender == 'Female')
					{
						$programssql .= "  AND gender in ('Female','Both')";
					}
				}
				$sug = Plansuggestions::findBySql($programssql)->asArray()->all();
				
				if($sug != [])
				{
				$model = [];
				foreach($sug as $key=>$value)
				{
					if(!empty($profile->age))
					{
						if(empty($profile->HbA1c) || $profile->HbA1c == "Dont Know")
						{
							if($value['age'] == '>')
							{
								if($value['agevalue'] <= $profile->age)
								{
								   $plan = Plans::find()->where(['planId'=>$value['planId']])->one();
								   $model[] = $plan;
								}
							}
							if($value['age'] == '<')
							{
								if($value['agevalue'] >= $profile->age)
								{
								   $plan = Plans::find()->where(['planId'=>$value['planId']])->one();
								   $model[] = $plan;
								}
							}
						}
						else
						{
							if($profile->HbA1c == "< 5.7 (Normal)")
							{
								$HbA1c ='5.7';
							}
							elseif($profile->HbA1c == "> 8.1 (Diabetes)")
							{
								$HbA1c ='8.1';
							}
							else
							{
								$HbA1c = str_replace(' (Diabetes)','',explode('to',$profile->HbA1c)[1]);								
							}
							if($value['age'] == '>')
							{
								
								if($value['hba1ccondition'] == '>')
								{
									if(($value['agevalue'] <= $profile->age) && ($value['hba1cvalue'] <= $HbA1c))
									{
									   $plan = Plans::find()->where(['planId'=>$value['planId']])->one();
									   $model[] = $plan;
									}
								}
								
								
								if($value['hba1ccondition'] == '<')
								{
									if(($value['agevalue'] <= $profile->age) && ($value['hba1cvalue'] >= $HbA1c))
									{
									   $plan = Plans::find()->where(['planId'=>$value['planId']])->one();
									   $model[] = $plan;
									}
								}
							}
							if($value['age'] == '<')
							{
								if($value['hba1ccondition'] == '>')
								{
									if(($value['agevalue'] >= $profile->age) && ($value['hba1cvalue'] <= $HbA1c))
									{
									   $plan = Plans::find()->where(['planId'=>$value['planId']])->one();
									   $model[] = $plan;
									}
								}
								if($value['hba1ccondition'] == '<')
								{
									if(($value['agevalue'] >= $profile->age) && ($value['hba1cvalue'] >= $HbA1c))
									{
									   $plan = Plans::find()->where(['planId'=>$value['planId']])->one();
									   $model[] = $plan;
									}
								}
							}
						}
					/*	if($model == [])
						{
							$plan = Plans::find()->where(['planId'=>$value['planId']])->one();						
							$model[] = $plan;
						}*/
					}
					else
					{
						$plan = Plans::find()->where(['planId'=>$value['planId']])->one();						
						$model[] = $plan;
					}
				   }
				   $newmodel = Plans::find()->where(['doctordriven'=>0,'general'=>1])->all();
				   $model = array_merge($model,$newmodel);
                }				
				else
				{
					print_r("hello");exit;
					$model = Plans::find()->where(['doctordriven'=>0,'general'=>1])->all();
				}
			}
				else
				{
					foreach($doctorplanId as $dkey=>$dvalue)
					{
						$plan = Plans::find()->where(['planId'=>$dvalue['programId']])->one();
						$model[] = $plan;
					}
				}
				return ['status' => true, 'message' => 'Plans', 'data' => $model]; 
            }  
	}
	
	public function actionPlans()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Plans();
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
				$model = [];
				$plansub = Userplans::find()->where(['access_token'=>$user->access_token,'Status'=>'Un-Subcribed'])->one();
				if(!empty($plansub))
				{
					$approval = Plans::find()->where(['planId'=>$plansub->planId])->asArray()->one();
					$approval['planId'] = intval($approval['planId']);
					$approval['discount'] = intval($approval['discount']);
					$approval['general'] = intval($approval['general']);
					$approval['doctordriven'] = intval($approval['doctordriven']);
					$approval['createdBy'] = intval($approval['createdBy']);
					$approval['updatedBy'] = intval($approval['updatedBy']);
					$approval['approval'] = 0;
					$model[]= $approval; 
				}
				else
				{
				$profile = Userprofile::find()->where(['userId'=>$user->id])->one();
				$programssql = "select * FROM plansuggestions INNER JOIN plans ON plans.planId = plansuggestions.planId where doctordriven=0";
				if(!empty($profile))
				{
					if($profile->gender == 'Male')
					{
						$programssql .= "  AND gender in ('Male','Both')";
					}
					elseif($profile->gender == 'Female')
					{
						$programssql .= "  AND gender in ('Female','Both')";
					}
				}
				$sug = Plansuggestions::findBySql($programssql)->asArray()->all();
				if($sug !=[])
				{
					foreach($sug as $key=>$value)
					{
						$model = [];
						$plan = $this->Plan($value,$profile);
						if($plan !=[])
						{
							foreach($plan as $k=>$v)
							{
								$plannew = Plans::find()->where(['planId'=>$v['planId']])->one();
								$model[] = $plannew;
							}
						}
						else
						{
							$plannew = Plans::find()->where(['planId'=>$value['planId']])->one();
							$model[] = $plannew;
						}
					}
				}
				else
				{
					$model = Plans::find()->where(['doctordriven'=>0,'general'=>1])->all();
				}
				
				}
				return ['status' => true, 'message' => 'Plans', 'data' => $model]; 
            }  
	}
	
	public function actionCustomerplans()
	{
		header('Access-Control-Allow-Origin: *'); 
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$model = new Plans();
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
            ->orderBy(['id'=> SORT_DESC]);            
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
				$model = [];
				$data =[];
				$user = User::find()->where(['username'=>$post['mobile']])->one();
				print_r($user); exit;
				if(!empty($user))
				{
					$plansub = Userplans::find()->where(['access_token'=>$user->access_token,'Status'=>'Subcribed'])->one();
					if(!empty($plansub))
					{
						$approval = Plans::find()->where(['planId'=>$plansub->planId])->asArray()->one();
						$approval['planId'] = intval($approval['planId']);
						$approval['discount'] = intval($approval['discount']);
						$approval['general'] = intval($approval['general']);
						$approval['doctordriven'] = intval($approval['doctordriven']);
						$approval['createdBy'] = intval($approval['createdBy']);
						$approval['updatedBy'] = intval($approval['updatedBy']);
						$approval['planstatus'] = $plansub->Status;
						$model[]= $approval; 
					}
					else
					{
					$profile = new Userprofile();
					$profile->gender = $post['gender'];
					$profile->HbA1c = $post['HbA1c'];
					$profile->age = $post['age'];
					$programssql = "select * FROM plansuggestions INNER JOIN plans ON plans.planId = plansuggestions.planId where doctordriven=0";
						if(!empty($profile))
						{
							if($profile->gender == 'Male')
							{
								$programssql .= "  AND gender in ('Male','Both')";
							}
							elseif($profile->gender == 'Female')
							{
								$programssql .= "  AND gender in ('Female','Both')";
							}
						}
						$sug = Plansuggestions::findBySql($programssql)->asArray()->all();
						//print_r($sug);exit;
						if($sug !=[])
						{
							foreach($sug as $key=>$value)
							{
								$model = [];
								$plan = $this->Plan($value,$profile);
								if($plan !=[])
								{
									foreach($plan as $k=>$v)
									{
										$plannew = Plans::find()->where(['planId'=>$v['planId']])->asArray()->one();
										$model[] = $plannew;
									}
								}
								else
								{
									$plannew = Plans::find()->where(['planId'=>$value['planId']])->asArray()->one();
									$model[] = $plannew;
								}
							}
						}
						else
						{
							$model = Plans::find()->where(['doctordriven'=>0,'general'=>1])->asArray()->all();
						}
					}
				}
				else
				{
					$profile = new Userprofile();
					$profile->gender = $post['gender'];
					$profile->HbA1c = $post['HbA1c'];
					$profile->age = $post['age'];
					$programssql = "select * FROM plansuggestions INNER JOIN plans ON plans.planId = plansuggestions.planId where doctordriven=0";
					if(!empty($profile))
						{
							if($profile->gender == 'Male')
							{
								$programssql .= "  AND gender in ('Male','Both')";
							}
							elseif($profile->gender == 'Female')
							{
								$programssql .= "  AND gender in ('Female','Both')";
							}
						}
						$sug = Plansuggestions::findBySql($programssql)->asArray()->all();
						
						if($sug !=[])
						{
							foreach($sug as $key=>$value)
							{
								$model = [];
								$plan = $this->Plan($value,$profile);
								if($plan !=[])
								{
									foreach($plan as $k=>$v)
									{
										$plannew = Plans::find()->where(['planId'=>$v['planId']])->asArray()->one();
										$model[] = $plannew;
									}
								}
								else
								{
									$plannew = Plans::find()->where(['planId'=>$value['planId']])->asArray()->one();
									$model[] = $plannew;
								}
							}
						}
						else
						{
							$model = Plans::find()->where(['doctordriven'=>0,'general'=>1])->asArray()->all();
						}
				}
				
					foreach($model as $key=>$value)
					{
						if(!isset($value['planstatus']))
						{
							$value['planstatus'] = "";
						}							
						$value['Price'] = intval($value['Price']);
						$value['offerPrice'] = intval($value['offerPrice']);
						$diagnosticstests = [];						
						$diagnosticamount = 0;						
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
						$value['diagnosticamount'] = $diagnosticamount-($diagnosticamount*($value['discount']/100));
						$value['diagnosticstests'] = $diagnosticstests;
						//print_r($value);exit;
						$data[] = $value;
				}				
                	
				
				return ['status' => true, 'message' => 'Plans', 'data' => $data]; 
            }  
	}
	
	public function actionCheckplannew()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$status = 'pending';
        $post = Yii::$app->request->post();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		else
		{

		$newuser = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC]);            
            if(empty($newuser))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
			{
				
			$user = User::find()
            ->where(['username' => $post['mobile']])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])->one();
				$planname = "";		
				if(!empty($user))
				{
					$plansub = Userplans::find()->where(['access_token'=>$user->access_token])->orderBy('userPlanId DESC')->one();
					if(!empty($plansub) && $plansub->Status != 'Expired')
					{
						$status = $plansub->Status;
						$plan = Plans::find()->where(['planId'=>$plansub->planId])->one();
						$planname = $plan->PlanName;
					}
			   }
		}
		}
	   return ['status' => true, 'message' => 'success', 'planstatus' => $status,'planname'=>$planname];
    }	
	
	public function actionCheckplan()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$status = 'pending';
        $post = Yii::$app->request->post();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        { 
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
		else  
		{
		$newuser = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC]);            
            if(empty($newuser))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
			{
			
				$planname = "";	
				$url = Yii::$app->request->hostInfo."/SugarFranchies/frontend/web/index.php?r=site/getcustomer&mobile=".$post['mobile'];
				$ch = curl_init($url);
				$payload = json_encode($post);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					   'Content-Type: application/json',
					   'Authorization: Bearer ' .$Authorization
				));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$resultDynamic = json_decode(curl_exec($ch));
				if(empty($resultDynamic->data))
				{
					$resultDynamic->data = "";
				}
				$user = User::find()
					->where(['username' => $post['mobile']])
					->andWhere(['=', 'status', User::STATUS_ACTIVE])
					->orderBy(['id'=> SORT_DESC])->one();						
				if(!empty($user))
				{
					$plansub = Userplans::find()->where(['access_token'=>$user->access_token])->orderBy('userPlanId DESC')->one();
					if(!empty($plansub) && $plansub->Status != 'Expired')
					{
							$status = $plansub->Status;
							$plan = Plans::find()->where(['planId'=>$plansub->planId])->one();
							$planname = $plan->PlanName;
					}
				}				
			}
		}
	   return ['status' => true, 'message' => 'success', 'planstatus' => $status,'planname'=>$planname,'data'=>$resultDynamic->data];
    }
	
	public function Plan($value,$profile)
	{
		$data = [];		
		if(empty($profile))
		{
			$sql = "select * FROM plans where doctordriven='0' AND general ='1'";
		}
		else
		{
			$sql = "select * FROM plansuggestions where ";
		}
		if(!empty($profile))
		{
			//$HbA1c = $profile->HbA1c;
			$HbA1c = "";
			if($profile->gender == 'Male')
			{
				$sql .= " gender in ('Male','Both')";
			}
			elseif($profile->gender == 'Female')
			{
				$sql .= " gender in ('Female','Both')";
			}
		}
		/*if(!empty($profile->HbA1c) && $profile->HbA1c != "Don't Know")
		{
			if($profile->HbA1c == "< 5.7 (Normal)")
			{
				$HbA1c ='5.7';
			}
			elseif($profile->HbA1c == "> 8.1 (Diabetes)")
			{
				$HbA1c ='8.1';
			}
			else
			{
				$HbA1c = str_replace(' (Diabetes)','',explode('to ',$profile->HbA1c)[1]);								
			}
		}*/
		if(!empty($profile->age) && $profile->age > 0 && !empty($value['age']))
		{
			$sql .=" AND '".$profile->age."' ".$value['age']." agevalue";
		}
		if(!empty($HbA1c) && !empty($value['hba1ccondition']))
		{
			$sql .=" AND '".$HbA1c."' ".$value['hba1ccondition']." hba1cvalue";
		}
		$sug = Plansuggestions::findBySql($sql)->asArray()->all();
		return $sug;
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
                return ['status' => true, 'message' => 'Plan', 'data' => $data]; 
            } 
	}
	public function actionPlanconsultations()
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
				$consultations =[];
				$approval = 1;
				$plansub = Userplans::find()->where(['access_token'=>$user->access_token,'Status'=>'Un-Subcribed'])->one();
				if(!empty($plansub))
				{
					$approval = 0;
				}
                $details = Plandetails::find()->select('day, endday')->where(['planId'=>$_GET['id']])->distinct()->asArray()->all();
				if($details != [])
				{
							foreach($details as $x=>$v)
							{
								$ditems = [];
								$consultations[$x]['day'] =  $v['day'];
								$consultations[$x]['endday'] = $v['endday'];
								$cdetails = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$_GET['id']])->asArray()->all();
								if($cdetails != []){
									foreach($cdetails as $dk=>$dv)
									{
										$ditems[] = $dv['itemName'];
									}	
								}
				                $consultations[$x]['text'] = $ditems;									
							}							
					}
                return ['status' => true, 'message' => 'Plan','approval'=>$approval, 'data' => $consultations]; 
            } 
	}	
    public function actionDiettrack()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		date_default_timezone_set("Asia/Kolkata");
		$data = array(); 
        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');		
        $post = Yii::$app->request->post();		
		if (empty($post['patId'])) 
        {
                return ['status' => false, 'message' => 'Patient Id is required'];
        }
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
				$model  = new Diettrack();
				$model->patId = $post['patId'];
				$model->mealType = $post['meal'];
				$mealtype = Mealtype::find()->where(['id'=>$post['meal']])->one();	
				$model->mealName = $mealtype->type;	
				$date = date('H:i');
				$model->time = $date;
				$model->itemId = $post['itemId'];	
				$item = Fooditems::find()->where(['itemId'=>$post['itemId']])->one();
				$model->itemName = $item->itemName;
				$model->quantity = $post['quantity'];
				$model->cal = $post['cal'];
				$model->createdDate = date('Y-m-d');
				$model->updatedDate = date('Y-m-d');
				$model->save();
				return ['status' => true, 'message' => 'Success'];
			}
	}
public function actionTestbooking()
	{
		$data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
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
				$data = [];
				$userprofile = Userprofile::find()->where(['userId'=>$user->id])->one();
                $access_token = $userprofile->access_token;
				date_default_timezone_set("Asia/Calcutta");
				$ordermodel = new Orders();
				$ordermodel->access_token = $access_token;
				$ordermodel->prebookingId = "46510";
				$ordermodel->bookingStatus = "Offline Booking";
				$ordermodel->test_type = $post['type'];
				$ordermodel->slotDate = $post['slotDate'];
				$ordermodel->slotTime = date('H:i');
				$ordermodel->createdDate = date('Y-m-d');
				$ordermodel->createdBy = $user->id;				
				if($ordermodel->save())
				{
					foreach($post['text'] as $key=>$value)
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
                return ['status' => true, 'message' => 'Success','orderId'=>$ordermodel->orderId ];               
        
		}
	}	
	
	public function actionTestreshedule()    {
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
              	$model = Orders::find()->where(['orderId'=>$post['bookingId']])->one();
				date_default_timezone_set("Asia/Calcutta");
				$model->slotTime =  date('H:i'); 
				$model->slotDate = $post['slotDate'];
				$model->bookingStatus = "Reshedule";
				$model->resheduleremarks = $post['remarks'];
				$model->updatedDate = date('Y-m-d'); 
				$model->updatedBy = $user->id; 
				$model->save();					
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->orderId];              
                
            }
        }
        
    }
	
    
}
?>