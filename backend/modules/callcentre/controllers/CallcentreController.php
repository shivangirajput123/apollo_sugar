<?php

namespace backend\modules\callcentre\controllers;

use Yii;
use backend\modules\callcentre\models\Callcentre;
use backend\modules\callcentre\models\CallcentreSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use yii\web\UploadedFile;
use yii\helpers\Json;
use frontend\models\Userplans;
use frontend\models\Userprofile;
use backend\modules\users\models\Dietician;
use backend\modules\users\models\Doctors;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use frontend\models\Orders;
use frontend\models\Orderitems;
use backend\modules\packages\models\Plandetails;
use backend\modules\packages\models\Plans;
use backend\modules\packages\models\ItemDetails;
/**
 * CallcentreController implements the CRUD actions for Callcentre model.
 */
class CallcentreController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Callcentre models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CallcentreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Callcentre model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
		$createdBy = User::find()->where(['id'=>$model->createdBy])->one();
		$updatedBy = User::find()->where(['id'=>$model->updatedBy])->one();
		$model->createdBy = $createdBy->username;
		$model->updatedBy = $updatedBy->username;
		$model->createdDate = date('d-M-Y',strtotime($model->createdDate ));
		$model->updatedDate = date('d-M-Y',strtotime($model->updatedDate ));
        return $this->render('view', [
            'model' => $model,
        ]);
    }
	
	public function actionPatients()
    {
        $searchModel = new CallcentreSearch();
        $dataProvider = $searchModel->statuschange(Yii::$app->request->queryParams);

        return $this->render('statuschange', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
    }
	
	public function actionDoctorconsultations()
    {
        $searchModel = new CallcentreSearch();
        $dataProvider = $searchModel->Doctorconsultations(Yii::$app->request->queryParams);

        return $this->render('doctorconsultations', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
    }
	
	public function actionTests()
    {
        $searchModel = new CallcentreSearch();
        $dataProvider = $searchModel->upcomingtests(Yii::$app->request->queryParams);

        return $this->render('upcomingtests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
    }
	
	public function actionDieticianconsultations()
    {
        $searchModel = new CallcentreSearch();
        $dataProvider = $searchModel->Dieticianconsultations(Yii::$app->request->queryParams);

        return $this->render('dieticianconsultations', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
    }

    /**
     * Creates a new Callcentre model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Callcentre();

       $model->cities = City::getCities();
		$model->locations = [];
		$model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save())
		{
			$model->profileImage = UploadedFile::getInstance($model,'profileImage');
            if(!empty($model->profileImage))
            {
                $imageName = time().$model->profileImage->name;
                $model->profileImage->saveAs(Yii::getAlias('@backend/web/images/profilepictures/').$imageName );
                $model->profileImage = 'images/profilepictures/'.$imageName;               
            }
            else
			{
                $model->profileImage = '';
            }
           return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Callcentre model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->cities = City::getCities();
		$model->locations = Location::getLocationsByID($model->cityId);
		$profileimage = $model->profileImage;
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			$model->profileImage = UploadedFile::getInstance($model,'profileImage');			
            if(!empty($model->profileImage))
            {
                $imageName = time().$model->profileImage->name;
                $model->profileImage->saveAs(Yii::getAlias('@backend/web/images/profilepictures/').$imageName );
                $model->profileImage = 'images/profilepictures/'.$imageName;               
            }
            else
			{
                $model->profileImage = $profileimage;
            }
			//print_r($model->profileImage);exit;
           return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
	
	
	public function actionDoctorbookingstatus($id)
    {
        $model = Slotbooking::find()->where(['bookingId'=>$id])->one();		
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
           return $this->redirect(['doctorconsultations','token'=>$model->access_token]);
        }

        return $this->render('doctorbookingstatus', [
            'model' => $model,
        ]);
    }


    public function actionDieticianbookingstatusbkp($token)
    {
		$model =  new Slotbooking();
		$data = Userplans::find()->where(['access_token'=>$token,'Status'=>'Subcribed'])->asArray()->one();
        $username = Userprofile::find()->where(['access_token'=>$token])->one();
		$plan = Plans::find()->where(['planId'=>$data['planId']])->one();
		$model->price = $data['price'];
		$model->username = $username->firstName;
		$model->planname = $plan->PlanName;
		$upcomingdata = [];
		$start = strtotime($data['updatedDate']);
		$end = strtotime(date('Y-m-d'));
		$days_between = ceil(abs($end - $start) / 86400);
		$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['planId'=>$plan->planId])->distinct()->asArray()->all();
		//print_r($upcomingdetailsarraydata);exit;
		$upcomingdetails = Plandetails::find()->where(['>=','day',$days_between])->andwhere(['planId'=>$data['planId']])->asArray()->one();
		if($upcomingdetails != [])
					{
						$events = Plandetails::find()->where(['planId'=>$data['planId'],'day'=>$upcomingdetails['day'],'endday'=>$upcomingdetails['endday']])->all();
						if($data['updatedDate'] == date('Y-m-d'))
						{
							$upcomingdetails['day'] = date('M d,Y',strtotime($data['updatedDate']));
									
						}
						else
						{
							$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($data['updatedDate'])));
						
						}
						$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($data['updatedDate'])));;
				
						if(!empty($events))
						{
							foreach($events as $key=>$value)
							{
								if($value->text == 1)
								{
									$doctorconsultation = Slotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
									//print_r(($upcomingdetails['day']));exit;
									if(empty($doctorconsultation) || $doctorconsultation->status != 'Completed')
									{
										$upcomingdata[$value->text] ='Doctor Consultation';
									}
								}
								elseif($value->text == 2)
								{
									$dieticianconsultation = Dieticianslotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
									//print_r(($upcomingdetails['day']));exit;
									if(empty($dieticianconsultation) || $dieticianconsultation->status != 'Completed')
									{
										$upcomingdata[$value->text] ='Dietician Consultation';
									}
								}
								else
								{
									$orderbookings = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['orders.access_token'=>$data['access_token'],'orderitems.itemId'=>$value->text])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('orderId DESC')->one();
									//print_r(date('Y-m-d',strtotime($upcomingdetails['day'])));exit;
									if(empty($orderbookings) || ($orderbookings->bookingStatus != 'Reports Generated' ))
									{
										 $orderitem = ItemDetails::find()->where(['itemId'=>$value->text])->one();
										 $upcomingdata[$value->text] =$orderitem->itemName;										 
									}
								}
							}
						}
					}
		$model->upcomingevents = $upcomingdata;
	  //print_r($upcomingdata);exit;					
        if ($model->load(Yii::$app->request->post())) 
		{
			//print_r($value);exit;
		   foreach($model->events as $key=>$value)
		   {
				if($value == 1)
				{
					$doctorconsultation = Slotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					if(!empty($doctorconsultation))
					{
						$doctorconsultation->status = $model->status;
						$doctorconsultation->save();
					}
					else
					{
						$bookingmodel = new Slotbooking();
						$bookingmodel->slotTime = date('H:i');
						$bookingmodel->slotDate = date('Y-m-d');
						$bookingmodel->access_token = $token;
					    //$bookingmodel->name = 'Manasa';
						$bookingmodel->status = $model->status;
						$bookingmodel->createdDate = date('Y-m-d');
						$bookingmodel->updatedDate = date('Y-m-d');
						$doctor = Doctors::find()->where(['userId'=>$data['doctorId']])->one();
						$bookingmodel->doctorId = $doctor->doctorId;
						$bookingmodel->save();
					}
				}
				elseif($value == 2)
				{
					$dieticianconsultation = Dieticianslotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					if(!empty($dieticianconsultation))
					{
						$dieticianconsultation->status = $model->status;
						$dieticianconsultation->save();
					}
					else
					{
						$bookingmodel = new Dieticianslotbooking();
						$bookingmodel->slotTime = date('H:i');
						$bookingmodel->slotDate = date('Y-m-d');
						$bookingmodel->access_token = $token;
					    //$bookingmodel->name = 'Manasa';
						$bookingmodel->status = $model->status;
						$bookingmodel->createdDate = date('Y-m-d');
						$bookingmodel->updatedDate = date('Y-m-d');
						$dietician = Dietician::find()->where(['userId'=>$data['dieticianId']])->one();
						$bookingmodel->dieticianId = $dietician->dieticianId;
						$bookingmodel->save();
					}
				}
				else
				{
					$orderbookings = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['orders.access_token'=>$data['access_token'],'orderitems.itemId'=>$value])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('orderId DESC')->one();
					if(!empty($orderbookings))
					{
						$orderbookings->bookingStatus = $model->status;
						$orderbookings->save();
					}
					else
					{
						$ordermodel = new Orders();
						$ordermodel->access_token = $token;
						$ordermodel->prebookingId = "46510";
						$ordermodel->bookingStatus = $model->status;
						$ordermodel->slotDate = date('Y-m-d');
						$ordermodel->slotTime = date('H:i');
						$ordermodel->createdDate = date('Y-m-d');
						if($ordermodel->save())
						{
							$orderitems = new Orderitems();
							$orderitems->orderId = $ordermodel->orderId;
							$orderitem = ItemDetails::find()->where(['itemId'=>$value])->one();										 
							$orderitems->itemId = $orderitem->itemId;
							$ordermodel->access_token = $token;
							$orderitems->itemName = $orderitem->itemName;
							$orderitems->price = $orderitem->rate;
							$orderitems->createdDate = date('Y-m-d');
							$orderitems->updatedDate = date('Y-m-d');
							$orderitems->save();
						}
					}
				}
		   }
           return $this->redirect(['patients']);
        }

        return $this->render('dieticianbookingstatus', [
            'model' => $model,
        ]);
    }
	
	
	public function actionDieticianbookingstatus($token)
    {
		$model =  new Slotbooking();
		$data = Userplans::find()->where(['access_token'=>$token,'Status'=>'Subcribed'])->one();
        $username = Userprofile::find()->where(['access_token'=>$token])->one();
		$plan = Plans::find()->where(['planId'=>$data['planId']])->one();
		$model->price = $data['price'];
		$model->username = $username->firstName;
		$model->planname = $plan->PlanName;
		$upcomingdata = [];
		$start = strtotime($data['updatedDate']);
		$end = strtotime(date('Y-m-d'));
		$days_between = ceil(abs($end - $start) / 86400);
		$event['doctorbooking'] = 1;
		$event['dietbooking'] = 1;
		$event['textbooking'] = 1;
		$doctorname = "";
		if($data['doctorId'])
		{
			$doctorname = User::find()->where(['id'=>$data['doctorId']])->one()->username;
		}
		$dieticanName = "";
		if($data['dieticianId'])
		{
			$dieticanName = User::find()->where(['id'=>$data['dieticianId']])->one()->username;
		}
		$callcentre = new Callcentre();
		$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['planId'=>$plan->planId])->distinct()->asArray()->all();
		$upcomingdetails = [];
		if($upcomingdetailsarraydata != [])
		{
			foreach($upcomingdetailsarraydata as $key=>$value)
			{
					$diet = Plandetails::find()->where(['day'=>$value['day'],'endday'=>$value['endday'],'text'=>2])->andwhere(['planId'=>$data['planId']])->asArray()->count();
		    		$doctor = Plandetails::find()->where(['day'=>$value['day'],'endday'=>$value['endday'],'text'=>1])->andwhere(['planId'=>$data['planId']])->asArray()->count();
					$event = $callcentre->eventchecking($value,$data,$data['access_token'],$doctorname,$dieticanName);							
					if($diet == 0)
					{
						$event['dietbooking'] = 1;
					}
					if($doctor == 0)
					{
						$event['doctorbooking'] = 1;
					}
					if($event['doctorbooking'] == 0 || $event['dietbooking'] == 0 || $event['textbooking'] == 0)
					{
						$upcomingdata = $callcentre->upcomingdetails($value,$data,$data['access_token'],$event,$doctorname,$dieticanName);
						$upcomingdetails = $value;
						break;
					}
							
			}
		}
		 $model->upcomingevents = $upcomingdata;
		if ($model->load(Yii::$app->request->post())) 
		{
		    //print_r($model->events);exit;
		   foreach($model->events as $key=>$value)
		   {
			  
				if($value == 1)
				{
					$doctorconsultation = Slotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					if(!empty($doctorconsultation))
					{
						$doctorconsultation->status = $model->status;
						$doctorconsultation->save();
					}
					else
					{
						$bookingmodel = new Slotbooking();
						$bookingmodel->slotTime = date('H:i');
						$bookingmodel->slotDate = date('Y-m-d');
						$bookingmodel->access_token = $token;
					    //$bookingmodel->name = 'Manasa';
						$bookingmodel->status = $model->status;
						$bookingmodel->createdDate = date('Y-m-d');
						$bookingmodel->updatedDate = date('Y-m-d');
						$doctor = Doctors::find()->where(['userId'=>$data['doctorId']])->one();
						$bookingmodel->doctorId = $doctor->doctorId;
						$bookingmodel->save();
					}
				}
				elseif($value == 2)
				{
					$dieticianconsultation = Dieticianslotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					if(!empty($dieticianconsultation))
					{
						$dieticianconsultation->status = $model->status;
						$dieticianconsultation->save();
					}
					else
					{
						$bookingmodel = new Dieticianslotbooking();
						$bookingmodel->slotTime = date('H:i');
						$bookingmodel->slotDate = date('Y-m-d');
						$bookingmodel->access_token = $token;
					    //$bookingmodel->name = 'Manasa';
						$bookingmodel->status = $model->status;
						$bookingmodel->createdDate = date('Y-m-d');
						$bookingmodel->updatedDate = date('Y-m-d');
						$dietician = Dietician::find()->where(['userId'=>$data['dieticianId']])->one();
						$bookingmodel->dieticianId = $dietician->dieticianId;
						$bookingmodel->save();
					}
				}
				else
				{
					$orderbookings = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['orders.access_token'=>$data['access_token'],'orderitems.itemId'=>$value])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('orderId DESC')->one();
					if(!empty($orderbookings))
					{
						$orderbookings->bookingStatus = $model->status;
						$orderbookings->save();
					}
					else
					{
						$ordermodel = new Orders();
						$ordermodel->access_token = $token;
						$ordermodel->prebookingId = "46510";
						$ordermodel->bookingStatus = $model->status;
						$ordermodel->slotDate = date('Y-m-d');
						$ordermodel->slotTime = date('H:i');
						$ordermodel->createdDate = date('Y-m-d');
						if($ordermodel->save())
						{
							$orderitems = new Orderitems();
							$orderitems->orderId = $ordermodel->orderId;
							$orderitem = ItemDetails::find()->where(['itemId'=>$value])->one();										 
							$orderitems->itemId = $orderitem->itemId;
							$ordermodel->access_token = $token;
							$orderitems->itemName = $orderitem->itemName;
							$orderitems->price = $orderitem->rate;
							$orderitems->createdDate = date('Y-m-d');
							$orderitems->updatedDate = date('Y-m-d');
							$orderitems->save();
						}
					}
				}
		   }
           return $this->redirect(['patients']);
        }

		 return $this->render('dieticianbookingstatus', [
            'model' => $model,
			
        ]);
    }
	public function upcomingdetails($data,$newmodel,$access_token,$event,$doctorname,$dieticanName)	{
		$upcomingdetails = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->asArray()->one();
		if(!empty($upcomingdetails))
		{
			$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($newmodel->updatedDate)));
			$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($newmodel->updatedDate)));;
			$upcomingdetails['plandetailId'] = strval($upcomingdetails['plandetailId']);				
			$upcomingdetails['planId'] = strval($upcomingdetails['planId']);
			$upcomingdetails['createdBy'] = strval($upcomingdetails['createdBy']);				
			$upcomingdetails['updatedBy'] = strval($upcomingdetails['updatedBy']);
			$duration = date_diff(date_create($upcomingdetails['day']), date_create($upcomingdetails['endday']));
			$upcomingdetails['duration'] = $duration->days;
			//print_r($event);exit;
			if($event['doctorbooking'] == 0)
		    {
				$upcomingdetails['type'] = 'doctor';
				$upcomingdetails['doctorName'] = $doctorname;					
				$doctorprofile = Doctors::find()->where(['userId'=>$newmodel->doctorId])->one();
				$upcomingdetails['doctorId'] = $doctorprofile->doctorId;
				$upcomingdetails['exp'] = $doctorprofile->experience;
				$upcomingdetails['qualification'] = $doctorprofile->qualification;		
				$booking = Slotbooking::find()->where(['access_token'=>$access_token])->andWhere(['!=','Status',"Cancel"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
				$upcomingdetails['text'] = 'Doctor Consultation';
				if(!empty($booking))
				{
						$upcomingdetails['bookingid'] = $booking->bookingId;
				}
				else
				{
							$upcomingdetails['bookingid'] = 0;
				}	
					$upcomingdetails['status'] = '';					
			}
			elseif($event['dietbooking'] == 0 && $newmodel->dieticianId != 0)
			{
					
					$upcomingdetails['type'] = 'dietician';
					$upcomingdetails['doctorName'] = $dieticanName;					
					$doctorprofile = Dietician::find()->where(['userId'=>$newmodel->dieticianId])->one();
					$upcomingdetails['doctorId'] = $doctorprofile->dieticianId;
					$upcomingdetails['exp'] = $doctorprofile->experience;
					$upcomingdetails['qualification'] = $doctorprofile->qualification;	
					$booking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andWhere(['!=','Status',"Cancel"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					$upcomingdetails['text'] = 'Dietician Consultation';
					if(!empty($booking))
					{
						$upcomingdetails['bookingid'] = $booking->bookingId;
					}
					else
					{
						$upcomingdetails['bookingid'] = 0;
					}
					$upcomingdetails['status'] = '';
			}
			elseif($event['textbooking'] == 0)
			{
				$upcomingdetails['type'] = 'tests';
				$upcomingdetails['doctorId'] = 0;
				$upcomingdetails['doctorName'] = '';
				$upcomingdetails['exp'] = '';
				$upcomingdetails['qualification'] = '';
				$orderbooking = Orders::find()->where(['access_token'=>$access_token])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->andwhere(['!=','bookingStatus','Report Generated'])->orderBy('orderId DESC')->one();
				if(empty($orderbooking))
				{
						$upcomingdetails['status'] = '';
				}
				else
				{
						$upcomingdetails['status'] = 'Booked';
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
				$newtest = Plandetails::find()->where(['NOT IN','text',$textarray])->andwhere(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->one();
				//print_r($newtest);exit;
				if($newtest!= [])
				{
					$upcomingdetails['text'] =$newtest['text'];						
				}
				$text = ItemDetails::find()->where(['itemId'=>$upcomingdetails['text']])->one()->itemName;
		        $upcomingdetails['text'] = $text;
			}
		}
		return $upcomingdetails;
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
	
	
	public function actionUpcomingteststatus($id)
    {
        $model = Orders::find()->where(['orderId'=>$id])->one();		
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
           return $this->redirect(['tests','token'=>$model->access_token]);
        }

        return $this->render('upcomingteststatus', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing Callcentre model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Callcentre model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Callcentre the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Callcentre::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
