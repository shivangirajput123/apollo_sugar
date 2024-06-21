<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\ChangePasswordForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use common\models\User;
use frontend\models\Glucose;
use frontend\models\GlucoseSearch;
use frontend\models\Userprofile;
use backend\modules\webinar\models\Webinars;
use backend\modules\packages\models\Plans;
use frontend\models\Userplans;
use backend\modules\users\models\Doctors;
use backend\modules\users\models\Dietician;
use backend\modules\clinics\models\Clinics;
use kartik\mpdf\Pdf;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','graphview','view-privacy'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					[
                		'actions' => ['request-password-reset', 'error'],
                		'allow' => true,
                		
                		],
                		[
                				'actions' => ['reset-password', 'error'],
                				'allow' => true,
                		
                		],
                			[
                		'actions' => ['change-password', 'error'],
                		'allow' => true,
                		
                		],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
	
	public function actionViewPrivacy() {
    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'content' => $this->renderPartial('privacy'),
        'options' => [
            // any mpdf options you wish to set
        ],
        'methods' => [
            'SetTitle' => 'Privacy Policy - Krajee.com',
            'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
            'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
            'SetFooter' => ['|Page {PAGENO}|'],
            'SetAuthor' => 'Kartik Visweswaran',
            'SetCreator' => 'Kartik Visweswaran',
            'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
        ]
    ]);
    return $pdf->render();
}

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $normalcount = Glucose::find()->select('access_token')->where(['createdDate'=>date('Y-m-d'),'Status'=>'Normal'])->distinct()->count();
		//print_r($normalcount);exit;
		$dangercount = Glucose::find()->select('access_token')->where(['createdDate'=>date('Y-m-d'),'Status'=>'Danger'])->distinct()->count();
		$moderatecount = Glucose::find()->select('access_token')->where(['createdDate'=>date('Y-m-d'),'Status'=>'Moderate'])->distinct()->count();
		$webnarcount = Webinars::find()->where(['>=','createdDate',date('Y-m-d')])->count();
		$pastwebnarcount = Webinars::find()->where(['<','createdDate',date('Y-m-d')])->count();
		
		
		$subscribecount = Userplans::find()->count();
		$searchModel = new GlucoseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	    if(Yii::$app->user->identity->roleId == 1 || Yii::$app->user->identity->roleId == 7)
		{
			$activeprograms = Plans::find()->where(['Status'=>'Active'])->count();
			$doctorcount = User::find()->where(['roleId'=>3])->count();
		    $dieticiancount = User::find()->where(['roleId'=>5])->count();
		}
		else
		{
			$activeprograms = Plans::find()->where(['Status'=>'Active','createdBy'=>Yii::$app->user->id])->count();
			$doctorcount = Doctors::find()->where(['createdBy'=>Yii::$app->user->id])->count();
		    $dieticiancount = Dietician::find()->where(['createdBy'=>Yii::$app->user->id])->count();
		}
		$programssql = "select  planId,count(*) as total  FROM userplans  GROUP BY planId ORDER BY count(*) DESC LIMIT 5";
		$model = Userplans::findBySql($programssql)->asArray()->all();
		$xvalues = [];		
		$yvalues = [];
		if($model != [])
		{
			foreach($model as $key=>$value)
			{
				$plan = Plans::find()->where(['planId'=>$value['planId']])->one();
				//print_r($plan);exit;
				$xvalues[] = '"'.$plan->PlanName.'"';
				$yvalues[] = $value['total'];
			}
		}	
		//print_r(implode(',',$xvalues));exit;
		$convertedcount = 0;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'normalcount'=>$normalcount,
			'dangercount'=>$dangercount,
			'moderatecount'=>$moderatecount,
			'webnarcount'=>$webnarcount,
			'activeprograms'=>$activeprograms,
			'doctorcount'=>$doctorcount,
			'dieticiancount'=>$dieticiancount,
			'subscribecount'=>$subscribecount,
			'convertedcount'=>$convertedcount,
			'pastwebnarcount'=>$pastwebnarcount,
			'xvalues'=>implode(',',$xvalues),
			'yvalues'=>implode(',',$yvalues)
        ]);
    }
	
	public function actionGraphview()
	{
		$xvalues = "";
		$Yvalues = [];
		$data = [];
		$user =Userprofile::find()->where(['access_token'=>$_GET['access_token']])->one();
		$access_token = $_GET['access_token'];
		if($_GET['type'] == 'day')
	    {
			$records = Glucose::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$_GET['access_token']])->all();
			foreach($records as $key=>$value)
			{
				if($xvalues == "")
				{
					$xvalues = '"'.date('g:i A',strtotime($value->time)).'"';
				}
				else
				{
					$xvalues = $xvalues.' , "'.date('g:i A',strtotime($value->time)).'"';
				}	
				//$xvalues = date('g:i A',strtotime($value->time));
				$Yvalues[$key] = intval($value->glucosevalue);
			} 
	   }
	   if($_GET['type'] == 'week')
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
						
						if($xvalues == "")
						{
							$xvalues = '"'.date("l",strtotime($start_week)).'"';
						}
						else
						{
							$xvalues = $xvalues.' , "'.date("l",strtotime($start_week)).'"';
						}	
						$morningavg = ceil(Glucose::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealid'=>1])->average('glucosevalue'));
						$afternoonavg = ceil(Glucose::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealid'=>2])->average('glucosevalue'));
					    $dinneravg = ceil(Glucose::find()->where(['createdDate'=>$start_week,'access_token'=>$access_token,'mealid'=>3])->average('glucosevalue'));
						$data[0][$i] = ceil($morningavg);
						$data[1][$i] = ceil($afternoonavg);
						$data[2][$i] = ceil($dinneravg);	
						$i++;
						$start_week = date('Y-m-d',strtotime("+1 day", strtotime($start_week)));
				}
				
				
			}
			if($_GET['type'] == 'month')
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
				//	$data[$i]['name'] = $name.' , '.$start_week.' To '.$end_week;;
					   if($xvalues == "")
						{
							$xvalues = '"'.$name.'"';
						}
						else
						{
							$xvalues = $xvalues.' , "'.$name.'"';
						}
					
					$morningavg = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealid'=>1])->average('glucosevalue');
					$afternoonavg = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealid'=>2])->average('glucosevalue');
					$dinneravg = Glucose::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token,'mealid'=>3])->average('glucosevalue');
					
					$data[0][$i] = ceil($morningavg);
					$data[1][$i] = ceil($afternoonavg);
					$data[2][$i] = ceil($dinneravg);						
				}
			}
			if($_GET['type'] == 'year')
			{
				
				$date = (date('Y')-1).'-'.date('m').'-'.date('d');
				$dates = $date.' To '.date('Y-m-d');				
				//$records = Glucose::find()->select('createdDate')->where(" Year( createdDate) = $currentYear ")->andwhere(['access_token'=>$access_token])->distinct()->all();
				//print_r($records);exit;
				for($i=0;$i<=12;$i++)
				{						
						//$data[$i]['name'] = date("M",strtotime($date));
						if($xvalues == "")
						{
							$xvalues = '"'.date("M",strtotime($date)).'"';
						}
						else
						{
							$xvalues = $xvalues.' , "'.date("M",strtotime($date)).'"';
						}
						$currentYear = date("Y",strtotime($date));
						$currentMonth = date("m",strtotime($date));
						$morningavg = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealid'=>1])->average('glucosevalue');
						$afternoonavg = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealid'=>2])->average('glucosevalue');
						$dinneravg = Glucose::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token,'mealid'=>3])->average('glucosevalue');
						
						$data[0][$i] = ceil($morningavg);
						$data[1][$i] = ceil($afternoonavg);
						$data[2][$i] = ceil($dinneravg);
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
				
			}
			
      		
		return $this->render('graphview',['morningdata'=>implode(',',$data[0]),'afternoondata'=>implode(',',$data[1]),'dinnerdata'=>implode(',',$data[2]),'xvalues'=>$xvalues,'user'=>$user
		]);
	}

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	public function actionChangePassword($id)
    {
    	try {
    		$model = new ChangePasswordForm();
    	} catch (InvalidParamException $e) {
    		throw new BadRequestHttpException($e->getMessage());
    	}
    
    	if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword($id)) {
    
    		\Yii::$app->session->setFlash('success', 'Password Changed!');
    		return $this->goHome();
    	}
    
    	return $this->render('changePassword', [
    			'model' => $model,
    	]);
    }
	public function actionRequestPasswordReset()
    {
    	$this->layout= 'main-login';
    	$model = new PasswordResetRequestForm();
    	if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    		if ($model->sendEmail()) {
    			Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
    			return $this->goHome();
    			 
    		} else {
    			Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
    		}
    	}
    
    	return $this->render('requestPasswordResetToken', [
    			'model' => $model,
    	]);
    }
    
    public function actionResetPassword($token)
    {
    	$this->layout= 'main-login';
    	try {
    		$model = new ResetPasswordForm($token);
    	} catch (InvalidParamException $e) {
    		throw new BadRequestHttpException($e->getMessage());
    	}
    
    	if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
    		Yii::$app->getSession()->setFlash('success', 'New password was saved.');
    
    		return $this->goHome();
    	}
    
    	return $this->render('resetPassword', [
    			'model' => $model,
    	]);
    }
}
