<?php
namespace frontend\controllers;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use frontend\models\Login;
use frontend\models\Userprofile;
use backend\modules\webinar\models\Webinars;
use backend\modules\webinar\models\Webinarenrolls;
use backend\modules\users\models\Bmivalues;
use backend\modules\common\models\Bp;
use frontend\models\Glucose;
use backend\modules\users\models\Usermeals;
use frontend\models\Feedback;
use frontend\models\Userplans;
use backend\modules\packages\models\Packages;
use backend\modules\packages\models\Packageitems;
use backend\modules\packages\models\ItemDetails;
use backend\modules\packages\models\Planinclusions;
use backend\modules\packages\models\Plans;
use backend\modules\notifications\models\Notifications;
use backend\modules\packages\models\Plandetails;
use backend\modules\users\models\Slots;
use backend\modules\users\models\Dslots;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use backend\modules\users\models\Doctors;
use backend\modules\users\models\Dietician;
use backend\modules\users\models\Prescription;
use backend\modules\users\models\Medicines;
use backend\modules\users\models\DoctorTests;
use frontend\models\Prescriptionpdfs;
use frontend\models\Medicinecart;
use frontend\models\Carttests;
use frontend\models\Orders;
use frontend\models\Orderitems;
use backend\modules\packages\models\Doctordrivenlinks;
use frontend\models\Doctorslotsession;
use frontend\models\Dieticianslotsession;
use DateTime;
use frontend\models\Steptracker;
use frontend\models\Customernotifications;

class SiteController extends Controller
{
    public function beforeAction($action)    {
        $this->enableCsrfValidation = false;
        if (parent::beforeAction($action)) {
            return true;
        }
        return false;
    }
    public function behaviors()    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
    public function actions()    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionSendOtp()    {       
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
            if (empty($model->mobilenumber)) 
            {
                return ['status' => false, 'message' => 'Mobile number is required'];
            } 
            else
            {
 	    	    $user = User::find()->where(['username'=>$model->mobilenumber])->andWhere(['=', 'status', 10])->one();             
                if($model->mobilenumber == '9177680088' ||  $model->mobilenumber == '9640746438' || $model->mobilenumber =='9848452600')
                {
                    $otp = "123456";
 		            if(empty($user))
                    {
                        $newuser = new SignupForm();
                        $newuser->username = $model->mobilenumber;
                        $userdata = $newuser->signupmobile($otp);
                        return ['status' => true, 'message' => 'OTP Sent to your mobile number','otp' => $otp];
                    }else
		   {

				   $user->otp_number = $otp;
                   $user->save();
			}
		   $data['id'] = $user->id;
		   $data['username'] = $user->username;
		   $data['otp_number'] = $otp;
		   return ['status' => true, 'message' => 'OTP Sent to Your Mobile Number','otp' => $otp];
        	}
		else
                {
                   $otp = '123456';
                } 
				$message = "OTP ".$otp." to login to ".$model->mobilenumber.", Apollo Clinic ";
				$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$model->mobilenumber.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
				$crl = curl_init();
				//curl_setopt($crl, CURLOPT_URL, $url);
				//curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
				//curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
               //$res = curl_exec($crl);
				$res = 1;
		        if($res) 
                {
                    if(empty($user))
                    {
                        $newuser = new SignupForm();
                        $newuser->username = $model->mobilenumber;
                        $userdata = $newuser->signupmobile($otp);
                        return ['status' => true, 'message' => 'OTP Sent to your mobile number','otp' => $otp];
                    }
                    else
                    {
                        $user->otp_number = $otp;
                        $user->save();
                        return ['status' => true, 'message' => 'OTP Sent to your mobile number','otp' => $otp];
                    } 
                }
                else
                 {
                    return ['status' => false, 'message' => 'OTP not Sent to your mobile number.'];
                 }
            }
        }
    }
	public function actionAdduser()    {       
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $post = json_decode(file_get_contents('php://input')); 
		$user = User::find()->where(['username'=>$post->mobilenumber])->andWhere(['=', 'status', 10])->one();             
        if(empty($user))
		{
			$otp = '123456';
			$newuser = new SignupForm();
            $newuser->username = $post->mobilenumber;
            $userdata = $newuser->signupmobile($otp);
			$userdata->status = 10;
			$userdata->save();
			$user = $userdata;						
		}
		$profile = Userprofile::find()->where(['access_token' => $user->access_token])->one();
		
        if(empty($profile))
		{
				$newprofile = new Userprofile();
				$newprofile->access_token = $user->access_token;
				$newprofile->userId = $user->id;
				$newprofile->firstName = $post->name;
				$newprofile->gender = $post->gender;
				$newprofile->age = $post->age;
				$newprofile->createdDate = date('Y-m-d');
                $newprofile->updatedDate = date('Y-m-d');				
				if(!$newprofile->save()){
					
				}
		}
		return ['status' => true, 'message' => 'Otp validated successfully', 'data'=>$user];
    }    
    public function actionValidateOtp() {
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
            if (empty($model->mobilenumber)) 
            {
                return ['status' => false, 'message' => 'Mobile number is required'];
            } 
            if (empty($model->otp_number)) 
            {
                return ['status' => false, 'message' => 'Otp number is required'];
            }
            $user = User::find()
                ->where(['username' => $model->mobilenumber])              
                ->orderBy(['id'=> SORT_DESC])
                ->one(); 
            if (!empty($user)) 
            {
                if ($user->otp_number == $model->otp_number) 
                {
                    $user->otp_number = null;
                    $user->status = User::STATUS_ACTIVE;
                    $user->save();
					$login = Login::find()->where(['userId'=>$user->id])->one();
					if(empty($login))
					{
						$newmodel = new Login();
						$newmodel->userId = $user->id;
						$newmodel->gcm_id = $model->gcm_id;
						$newmodel->device_info = $model->device_info;
						$newmodel->app_info = $model->app_info;
						$newmodel->createdDate = date('Y-m-d H:i;s');
						$newmodel->updatedDate = date('Y-m-d H:i;s');           
						$newmodel->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
						$newmodel->save();
					}
					else
					{
							$login->gcm_id = $model->gcm_id;
							$login->save();
					}
					$profile = Userprofile::find()->where(['access_token' => $user->access_token])->one();
					if(empty($profile))
					{
						$status = 'Pending';
					}
					else
					{
						$status = 'Completed';
					}
                    return ['status' => true, 'message' => 'Otp validated successfully', 'profilestatus' => $status,'data'=>$user];
                }
                else 
				{
                    return ['status' => false, 'message' => 'OTP is not valid'];
                }
            }
            else
            {
                return ['status' => false, 'message' => 'Mobile number is invalid'];
            }
       }
    }
    public static function randomNumber($length) {
        $string = "";
        $codeAlphabet = "0123456789";
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < $length; $i++) {
            $string .= $codeAlphabet[mt_rand(0, $max - 1)];
        }
        return $string;
    }
    public function actionProfileUpdate()    {
        $model = new Userprofile();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        if ($model->load(\Yii::$app->request->post(), '') ) 
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
                $birth_date = $model->DOB;
		        $age= date("Y") - date("Y", strtotime($birth_date));
                $model->age = $age;
                $model->userId = $user->id;
                $model->createdDate = date('Y-m-d');
                $model->updatedDate = date('Y-m-d');
                $profile = Userprofile::find()->where(['access_token' => str_replace('Bearer ','',$Authorization)])->one();
                if (!empty($_FILES['profilePic']['name'])) 
				{
                    if (($_FILES['profilePic']['size'] > 2000000) || $_FILES['profilePic']['size'] == '0') {
                        return ['status' => false, 'message' => 'Image size should be less than 2MB'];
                    }
                    else 
                    {
                        $presc_path = \yii\web\UploadedFile::getInstanceByName('profilePic');
                        $path = "/frontend/web/profileImages/";
                        if(!is_dir($path))
                        {
                            \yii\helpers\FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
                        }
                        $presc_path->saveAs(Yii::getAlias('@frontend/web/profileImages/').$presc_path);
                        $file_path = '/frontend/web/profileImages/'.$presc_path;
                    }
                }
				$file_path =  '';
                if(!empty($profile))
                {
                    $profile->age =  $age; 
                    $profile->firstName =  $model->firstName; 
                    $profile->lastName =  $model->lastName;  
                    $profile->gender =  $model->gender; 
                    if(!empty($_FILES['profilePic']['name']))
                    {
                        $profile->profilePic =  $file_path; 
                    }                     
                    $profile->DOB =  date('Y-m-d',strtotime($model->DOB)); 
                    $profile->weight =  $model->weight;  
                    $profile->height =  $model->height; 
                    $profile->familyhistory =  $model->familyhistory; 
                    $profile->glucosescore =  $model->glucosescore; 
                    $profile->diabeticcondition =  $model->diabeticcondition; 
					$profile->period =  $model->period; 
                    $profile->manageDiabetes =  $model->manageDiabetes; 
					$profile->typicalDay =  $model->typicalDay; 
					$profile->expLowSugar =  $model->expLowSugar; 
                    $profile->HbA1c =  $model->HbA1c;
                    $profile->updatedDate =  date('Y-m-d'); 
					$profile->save();
                    return ['status' => true, 'message' => 'Profile Updated successfully'];
                }
                else
                {
		    $model->access_token = str_replace('Bearer ','',$Authorization);
 		    $model->DOB =  date('Y-m-d',strtotime($model->DOB));
                    $model->profilePic =$file_path;
					$model->save();
                    return ['status' => true, 'message' => 'Profile Updated successfully'];
                }
            }
        }
  }
    public function actionDemographydata()    {
        $model = new Userprofile();
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
                $profile = Userprofile::find()->where(['access_token' => str_replace('Bearer ','',$Authorization)])->one();
                if(!empty($profile))
                {               
					if(!empty($post['weight']))
					{
					  $profile->weight =  $post['weight'];
					}
                    if(!empty($post['height']))
					{					
                       $profile->height =  $post['height']; 
				    }
					/*$Bmivalues = new Bmivalues();
					$Bmivalues->access_token = $access_token;
					$Bmivalues->weight =  $post['weight'];  
                    $Bmivalues->height =  $post['height'];
					$Bmivalues->BMI =  $post['BMI'];	
					$Bmivalues->createdDate =  date('Y-m-d');
					$Bmivalues->updatedDate =  date('Y-m-d');
					$Bmivalues->save();	*/
                    if(!empty($post['diabeticcondition']))
					{					
						$profile->diabeticcondition =  $post['diabeticcondition']; 
					}
					if(!empty($post['period']))
					{
						$profile->period =  $post['period'];
					}	
                    if(!empty($post['manageDiabetes']))
					{					
                      $profile->manageDiabetes = $post['manageDiabetes']; 
					}
					if(!empty($post['Pregnancystatus']))
					{					
                      $profile->Pregnancystatus = $post['Pregnancystatus']; 
					}
					if(!empty($post['existingCondtions']))
					{					
                      $profile->existingCondtions = $post['existingCondtions']; 
					}
					if(!empty($post['typicalDay']))
					{					
                      $profile->typicalDay = $post['typicalDay']; 
					}
					if(!empty($post['expLowSugar']))
					{					
                      $profile->expLowSugar = $post['expLowSugar']; 
					}
					if(!empty($post['HbA1c']))
					{					
                      $profile->HbA1c = $post['HbA1c']; 
					}
					if(!empty($post['HbA1c']))
					{
						$profile->Status = $post['Status'];
					}
                    $profile->updatedDate =  date('Y-m-d'); 
					$profile->save();
                    return ['status' => true, 'message' => 'Updated successfully'];
                }       
           }
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
                $profile = Userprofile::find()->where(['access_token' => str_replace('Bearer ','',$Authorization)])->one();
				$profile->weight = (number_format($profile->weight,2));
                return ['status' => true, 'message' => 'Profile View', 'data' => [$profile]];             
            }
        } 
    }    
	}    
    public static function actionWebinars()    {
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
				$date = date('Y-m-d');
                $query = Webinars::find()->where(['>=','PublishDate',$date])->andWhere(['Status'=>'Active'])->all();
                
				foreach($query as $key=>$value)
                {
					$profile = Userprofile::find()->where(['access_token' => str_replace('Bearer ','',$Authorization)])->one();
					if($value->sent == 'forall')
					{
                        $data[] = $value;
					}
					elseif($value->sent == $profile->gender)
					{
						$data[] = $value;
					}
					elseif($value->sent == 'enrolls')
					{
						$Webinarenrolls = Webinarenrolls::find()->where(['access_token' => str_replace('Bearer ','',$Authorization)])->one();
						if(!empty($Webinarenrolls))
						{
							$data[] = $value;
						}						
					}
                }                
                return ['status' => true, 'message' => 'Webinars', 'data' => $data];             
            }
        } 
    }   
    public function actionEnrollment()    {
        $model = new Webinarenrolls();
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
                $enrollment = Webinarenrolls::find()->where(['access_token' => $access_token,'webinarId'=>$post['webinarId']])->one();
                if(empty($enrollment))
                {    
					$model->webinarId = $post['webinarId'];
					$model->access_token =  $access_token; 
					$model->createdDate =  date('Y-m-d'); 
					$model->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
      				$model->save();
                    return ['status' => true, 'message' => 'Enrollment success'];
                } 
				else
				{
					return ['status' => false, 'message' => 'Already Enrollment For this webinar'];
				}
            }
        }
    }
	public static function actionAddBp()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Bp();
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
				$model->access_token = $access_token;
                $model->createdDate = date('Y-m-d');
                $model->updatedDate = date('Y-m-d');
				$model->save();			
                return ['status' => true, 'message' => 'Bp Added Successfully']; 
            }            
        }
    }
	public static function actionAddbmi()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $model = new Bp();
        $data = array();
		date_default_timezone_set("Asia/Calcutta");
        if ($model->load(\Yii::$app->request->post(), '') ) 
        {
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
				$pastrecord = Bmivalues::find()->where(['access_token'=>$access_token])->orderBY('bmiId DESC')->one();
				if(empty($pastrecord))
				{
					$pastbmi = $post['BMI'];
					$pastweight = $post['weight'];
					$pasthba1c = $post['hba1c'];
				}
				else
				{
					$pastbmi = $pastrecord->BMI;
					$pastweight = $pastrecord->weight;
					$pasthba1c = $pastrecord->hba1c;
				}
				$Bmivalues = new Bmivalues();
				$Bmivalues->access_token = $access_token;
				$Bmivalues->weight =  $post['weight'];  
                $Bmivalues->height =  $post['height'];
				$Bmivalues->BMI =  $post['BMI'];
				$Bmivalues->hba1c =  $post['hba1c'];
				$Bmivalues->pastbmi =  $pastbmi;
				$Bmivalues->pasthba1c =  $pasthba1c;
				$Bmivalues->pastweight =  $pastweight;						
				$Bmivalues->createdDate =  date('Y-m-d');
				$Bmivalues->updatedDate =  date('Y-m-d');
				$Bmivalues->save();			
                return ['status' => true, 'message' => 'Bp Added Successfully']; 
            }            
        }
    }
	public  function actionPercentagevalues()    {
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
				 $access_token = str_replace('Bearer ','',$Authorization);
                    if($get['version'] < 1.9)
					{
						$require = true;
					}
					else
					{
					   $require = false; 
					}
					
				$date = date('Y-m-d');
				$enrollmentstatus = 0;
				$webinarcount = 0;
				$pastbmi = "0.00";
				$presentbmi = "0.00";
				$pastweight = "0.00";
				$presentweight = "0.00";
				$pasthba1c = "0.00";
				$presenthba1c = "0.00";
				$bmistatus = 0;
				$weightstatus = 0;
				$hba1cstatus = 0;
				$pastrecord = Bmivalues::find()->where(['access_token'=>$access_token])->orderBY('bmiId DESC')->one();
				if(!empty($pastrecord))
				{
					$pastbmi = $pastrecord->pastbmi;
					if(!empty($pastrecord->BMI))
					{
						$presentbmi = ($pastrecord->BMI);
					}
					$pasthba1c = ($pastrecord->pasthba1c);
					$presenthba1c = ($pastrecord->hba1c);
					$pastweight = ($pastrecord->pastweight);
					$presentweight = ($pastrecord->weight);
					if($presentbmi > $pastbmi){
						$bmistatus = 1;
					}
					elseif($presentbmi < $pastbmi){
						$bmistatus = 2;
					}
					if($presentweight > $pastweight){
						$weightstatus = 1;
					}
					elseif($presentweight < $pastweight){
						$weightstatus = 2;
					}
					if($presenthba1c > $pasthba1c){
						$hba1cstatus = 1;
					}
					elseif($presenthba1c < $pasthba1c){
						$hba1cstatus = 2;
					}
				}
				

				$query = Webinars::find()->where(['PublishDate'=>$date,'Status'=>'Active'])->one();
				if(!empty($query)){
                $enrollment = Webinarenrolls::find()->where(['access_token' => $access_token,'webinarId'=>$query->webnarId])->one();
                if(empty($enrollment))
                {    
					$enrollmentstatus = 0;
                } 
				else
				{
					$enrollmentstatus = 1;
				}
				$webinarcount = 1;
				}
				$profile = Userprofile::find()->where(['access_token' => $access_token])->asArray()->one();
				if($profile == [])
				{
					$profilestatus = 0;
				}
				else
				{
					$value = 0;
					$total = 14;
					$fields = new Userprofile();
					$arrFields = array_keys($fields->attributes); 
					for($i=2;$i<=15;$i++)
					{
						 if(!empty($profile[$arrFields[$i]]) && $profile[$arrFields[$i]] != 'null'){
							$value = $value +1;
						 }
					}
					$profilestatus = ceil(($value/$total)*100);
				}
				$readtype= 3;
				$averageglucose = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token])->average('glucosevalue'));
				$glucosevalue = Glucose::find()->select('readingid')->where(['createdDate'=>$date,'access_token' => $access_token,'readingid'=>[1,2,3]])->distinct()->count();
				$typearray = [];
				$morning = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealid'=>'1'])->average('glucosevalue'));
				$afternoon = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealid'=>'2'])->average('glucosevalue'));
				$night = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealid'=>'3'])->average('glucosevalue'));
				$typearray['morning'] = $morning;
				$typearray['afternoon'] = $afternoon;
				$typearray['night'] = $night;
				$averageglucoseper = ceil(($glucosevalue/$readtype)*100);
				$aftermeal = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'readingid'=>3])->average('glucosevalue'));
				$overnightglucose = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'readingid'=>2])->average('glucosevalue'));
				$avgBMI = Bmivalues::find()->where(['access_token' => $access_token])->one();
				$avgsystolic = ceil(Bp::find()->where(['access_token' => $access_token])->average('SystolicValue'));
				$avgdiastolic = ceil(Bp::find()->where(['access_token' => $access_token])->average('DiastolicValue'));
				$avgbp = $avgsystolic.'/'.$avgdiastolic;
				$mealavg = (Usermeals::find()->where(['access_token' => $access_token,'createdDate'=>$date])->average('cal'));
				$mealarray = [];
				$morningmeal = (Usermeals::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealtype'=>'breakfast'])->average('cal'));
				$afternoonmeal = (Usermeals::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealtype'=>'lunch'])->average('cal'));
				$nightmeal = (Usermeals::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealtype'=>'dinner'])->average('cal'));
				if(empty($morningmeal)){
					$morningmeal = floatval(0);
				}
				if(empty($afternoonmeal)){
					$afternoonmeal = floatval(0);
				}
				if(empty($nightmeal)){
					$nightmeal = floatval(0);
				}
				$mealarray['morning'] = ceil($morningmeal);
				$mealarray['afternoon'] = ceil($afternoonmeal);
				$mealarray['night'] = ceil($nightmeal);
				if($averageglucose == null)
				{
					$averageglucose = 0;
				}
				if($aftermeal == null)
				{
					$aftermeal = 0;
				}
				if($overnightglucose == null)
				{
					$overnightglucose = 0;
				}
				if($avgBMI == null)
				{
					$avgBMI = 0;
				}
				else
				{
					$avgBMI = intval($avgBMI->BMI);
				}
				if($avgbp == '/')
				{
					$avgbp = "0";
				}
				if($mealavg == null)
				{
					$mealavg = 0;
				}
				$morningsysvalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['<','time','12:00'])->average('SystolicValue'));
				$afternoonsysvalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>=','time','12:00'])->andWhere(['<=','time','16:00'])->average('SystolicValue'));
				$nightmealsysvalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>','time','16:00'])->average('SystolicValue'));
				$morningdiavalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue'));
				$afternoondiavalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>=','time','12:00'])->andWhere(['<=','time','16:00'])->average('DiastolicValue'));
				$nightmealdiavalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>','time','16:00'])->average('DiastolicValue'));
				$bparray['morning'] = $morningsysvalue.'/'.$morningdiavalue;
				$bparray['afternoon'] = $afternoonsysvalue.'/'.$afternoondiavalue;
				$bparray['night'] = $nightmealsysvalue.'/'.$nightmealdiavalue;
				$stepcount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('count');
				$calcountnew= Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->one();
				$distancecountnew = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->one();
				//print_r($calcountnew->cal);exit;
				if(!empty($calcountnew))
				{
					$calcount = $calcountnew->cal;
				}
				if(!empty($distancecountnew))
				{
					$distancecount = $distancecountnew->cal;
				}
				$planstatus = false;
				$planExpiryDate = "";
				$planexpirystatus = false;
				$planId = 0;
				$noofdays = 0;
				$isplan = Userplans::find()->where(['access_token'=>$access_token,'Status'=>'Subcribed'])->orderBy('userPlanId DESC')->one();
				if(!empty($isplan))
				{
					if($isplan->planExpiryDate <= date('Y-m-d'))
					{
						$isplan->Status = 'Expired';
						$isplan->save();
						$planexpirystatus = true;
						$planId = $isplan->planId;
					}
					else
					{
						$planstatus = true;
						$planExpiryDate =$isplan->planExpiryDate;
						$now = time();
						$your_date = strtotime($planExpiryDate);
						$datediff = $your_date - $now;
						$noofdays = round($datediff / (60 * 60 * 24));
					}
				}
				else
				{
					$planExpiry = Userplans::find()->where(['access_token'=>$access_token])->orderBy('userPlanId DESC')->one();
					if(!empty($planExpiry))
					{
						if($planExpiry->Status == 'Expired')
						{
							$planexpirystatus = true;
							$notificationexpiry = $this->notificationeexpiry($access_token);					
						}
						$planId = $planExpiry->planId;
					}
					
				}
				if($noofdays < 10 && $noofdays > 0)
				{
					$notification = $this->notificationbeforeexpiry($access_token,$noofdays);					
				}
				return ['status' => true, 'message' => 'success','forceupdate'=>$require,'planstatus'=>$planstatus,
				'webinarcount'=>$webinarcount,
				'planexpirystatus'=>$planexpirystatus,
				'planId'=>$planId,
				'noofdays'=>$noofdays,
				'planExpiryDate'=>$planExpiryDate,
				'pastbmi'=>$pastbmi,
				'presentbmi'=>$presentbmi,
				'bmistatus'=>$bmistatus,				
				'pasthba1c'=>$pasthba1c,
				'presenthba1c'=>$presenthba1c,
				'hba1cstatus'=>$hba1cstatus,
				'pastweight'=>$pastweight,
				'presentweight'=>$presentweight,
				'weightstatus'=>$weightstatus,
				'name'=>$profile['firstName'],'enrollmentstatus'=>$enrollmentstatus,'profilestatus'=>$profilestatus,
				'stepcount'=>ceil($stepcount),'calcount'=>ceil($calcount),'distancecount'=>ceil($distancecount),
				'averageglucose'=>$averageglucose,'nonfasting'=>$aftermeal,'fasting'=>$overnightglucose,'glucosearray'=>$typearray,'avgBMI'=>$avgBMI,'avgbp'=>$avgbp,'bparray'=>$bparray,'mealavg'=>ceil($mealavg),'mealarray'=>$mealarray];
            }  
    }
	public function notificationbeforeexpiry($access_token,$noofdays)
	{
		//print_r($access_token);exit;
		date_default_timezone_set("Asia/Calcutta");
		$data = [];
		$id = User::find()->where(['access_token'=>$access_token])->one();
		$value = Login::find()->where(['userId'=>$id->id])->one();;
		$time = date('H:i');
		$data['title'] = "Title";
		$data['body'] = "Oh No, your subscription is about to end in ".$noofdays."day(s), renew it before you lose it.";
		$check = Customernotifications::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d'),'type'=>'Rexpiry'])->one();
		if(empty($check))
		{
			$newmodel  = new Customernotifications();
			$newmodel->access_token = $access_token;
			$newmodel->message = $data['body'];
			$newmodel->time = $time;
			$newmodel->type = 'Rexpiry';
			$newmodel->createdDate = date('Y-m-d');
			$newmodel->updatedDate = date('Y-m-d');
			if(!$newmodel->save())
			{
				print_r($newmodel->errors);exit;
			}
			if(!empty($value))
			{
				$fcm_id[] = $value->gcm_id;
				$model = new Notifications();
				$send = $model->addUserFcm($data, $fcm_id);
			}
		}
		//return $data;
	}	
	public function notificationeexpiry($access_token)
	{
		//print_r($access_token);exit;
		date_default_timezone_set("Asia/Calcutta");
		$data = [];
		$id = User::find()->where(['access_token'=>$access_token])->one();
		$value = Login::find()->where(['userId'=>$id->id])->one();;
		$time = date('H:i');
		$data['title'] = "Title";
		$data['body'] = "Your subscription has ended, renew it now to get back on track with your diabetes";
		$check = Customernotifications::find()->where(['access_token'=>$access_token,'createdDate'=>date('Y-m-d'),'type'=>'expired'])->one();
		if(empty($check))
		{
			$newmodel  = new Customernotifications();
			$newmodel->access_token = $access_token;
			$newmodel->message = $data['body'];
			$newmodel->time = $time;
			$newmodel->type = 'expired';
			$newmodel->createdDate = date('Y-m-d');
			$newmodel->updatedDate = date('Y-m-d');
			if(!$newmodel->save())
			{
				print_r($newmodel->errors);exit;
			}
			if(!empty($value))
			{
				$fcm_id[] = $value->gcm_id;
				$model = new Notifications();
				$send = $model->addUserFcm($data, $fcm_id);
			}
		}
		//return $data;
	}	
	public static function actionPercentagevaluesnew()    {
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
				 $access_token = str_replace('Bearer ','',$Authorization);
                    if($get['version'] < 1.9)
					{
						$require = true;
					}
					else
					{
					   $require = false; 
					}
				$date = date('Y-m-d');
				$enrollmentstatus = 0;
				$webinarcount = 0;
				$pastbmi = 0;
				$presentbmi = 0;
				$pastweight = 0;
				$presentweight = 0;
				$pasthba1c = 0;
				$presenthba1c = 0;
				$query = Webinars::find()->where(['PublishDate'=>$date,'Status'=>'Active'])->one();
				if(!empty($query)){
                $enrollment = Webinarenrolls::find()->where(['access_token' => $access_token,'webinarId'=>$query->webnarId])->one();
                if(empty($enrollment))
                {    
					$enrollmentstatus = 0;
                } 
				else
				{
					$enrollmentstatus = 1;
				}
				$webinarcount = 1;
				}
				$profile = Userprofile::find()->where(['access_token' => $access_token])->asArray()->one();
				if($profile == [])
				{
					$profilestatus = 0;
				}
				else
				{
					$value = 0;
					$total = 14;
					$fields = new Userprofile();
					$arrFields = array_keys($fields->attributes); 
					for($i=2;$i<=15;$i++)
					{
						 if(!empty($profile[$arrFields[$i]])){
							$value = $value +1;
						 }
					}
					$profilestatus = ceil(($value/$total)*100);
				}
				$readtype= 3;
				$averageglucose = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token])->average('glucosevalue'));
				$glucosevalue = Glucose::find()->select('readingid')->where(['createdDate'=>$date,'access_token' => $access_token,'readingid'=>[1,2,3]])->distinct()->count();
				$typearray = [];
				$morning = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealid'=>'1'])->average('glucosevalue'));
				$afternoon = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealid'=>'2'])->average('glucosevalue'));
				$night = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealid'=>'3'])->average('glucosevalue'));
				$typearray['morning'] = $morning;
				$typearray['afternoon'] = $afternoon;
				$typearray['night'] = $night;
				$averageglucoseper = ceil(($glucosevalue/$readtype)*100);
				$aftermeal = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'readingid'=>3])->average('glucosevalue'));
				$overnightglucose = ceil(Glucose::find()->where(['createdDate'=>$date,'access_token' => $access_token,'readingid'=>2])->average('glucosevalue'));
				$avgBMI = Bmivalues::find()->where(['access_token' => $access_token])->one();
				$avgsystolic = ceil(Bp::find()->where(['access_token' => $access_token])->average('SystolicValue'));
				$avgdiastolic = ceil(Bp::find()->where(['access_token' => $access_token])->average('DiastolicValue'));
				$avgbp = $avgsystolic.'/'.$avgdiastolic;
				$mealavg = (Usermeals::find()->where(['access_token' => $access_token,'createdDate'=>$date])->average('cal'));
				$mealarray = [];
				$morningmeal = (Usermeals::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealtype'=>'breakfast'])->average('cal'));
				$afternoonmeal = (Usermeals::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealtype'=>'lunch'])->average('cal'));
				$nightmeal = (Usermeals::find()->where(['createdDate'=>$date,'access_token' => $access_token,'mealtype'=>'dinner'])->average('cal'));
				if(empty($morningmeal)){
					$morningmeal = floatval(0);
				}
				if(empty($afternoonmeal)){
					$afternoonmeal = floatval(0);
				}
				if(empty($nightmeal)){
					$nightmeal = floatval(0);
				}
				$mealarray['morning'] = ceil($morningmeal);
				$mealarray['afternoon'] = ceil($afternoonmeal);
				$mealarray['night'] = ceil($nightmeal);
				if($averageglucose == null)
				{
					$averageglucose = 0;
				}
				if($aftermeal == null)
				{
					$aftermeal = 0;
				}
				if($overnightglucose == null)
				{
					$overnightglucose = 0;
				}
				if($avgBMI == null)
				{
					$avgBMI = 0;
				}
				else
				{
					$avgBMI = intval($avgBMI->BMI);
				}
				if($avgbp == '/')
				{
					$avgbp = "0";
				}
				if($mealavg == null)
				{
					$mealavg = 0;
				}
				$morningsysvalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['<','time','12:00'])->average('SystolicValue'));
				$afternoonsysvalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>=','time','12:00'])->andWhere(['<=','time','16:00'])->average('SystolicValue'));
				$nightmealsysvalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>','time','16:00'])->average('SystolicValue'));
				$morningdiavalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['<','time','12:00'])->average('DiastolicValue'));
				$afternoondiavalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>=','time','12:00'])->andWhere(['<=','time','16:00'])->average('DiastolicValue'));
				$nightmealdiavalue = (Bp::find()->where(['createdDate'=>$date,'access_token' => $access_token])->andWhere(['>','time','16:00'])->average('DiastolicValue'));
				$bparray['morning'] = $morningsysvalue.'/'.$morningdiavalue;
				$bparray['afternoon'] = $afternoonsysvalue.'/'.$afternoondiavalue;
				$bparray['night'] = $nightmealsysvalue.'/'.$nightmealdiavalue;
				$stepcount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('count');
				$calcount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('cal');
				$distancecount = Steptracker::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->sum('distance');
				$planstatus = false;
				$isplan = Userplans::find()->where(['access_token'=>$access_token,'Status'=>'Subcribed'])->one();
				if(!empty($isplan))
				{
					$planstatus = true;
				}
				return ['status' => true, 'message' => 'success','forceupdate'=>$require,'planstatus'=>$planstatus,
				'webinarcount'=>$webinarcount,
				'pastbmi'=>$pastbmi,
				'presentbmi'=>$presentbmi,
				'pasthba1c'=>$pasthba1c,
				'presenthba1c'=>$presenthba1c,
				'pastweight'=>$pastweight,
				'presentweight'=>$presentweight,'name'=>$profile['firstName'],'enrollmentstatus'=>$enrollmentstatus,'profilestatus'=>$profilestatus,
				'stepcount'=>ceil($stepcount),'calcount'=>ceil($calcount),'distancecount'=>ceil($distancecount),
				'averageglucose'=>$averageglucose,'nonfasting'=>$aftermeal,'fasting'=>$overnightglucose,'glucosearray'=>$typearray,'avgBMI'=>$avgBMI,'avgbp'=>$avgbp,'bparray'=>$bparray,'mealavg'=>ceil($mealavg),'mealarray'=>$mealarray];
            }  
        }	
	public function actionFeedback()    {
        $model = new Feedback();
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
				$model->medicines = $post['medicines'];
				$model->symptoms = $post['symptoms'];
              	$model->access_token =  $access_token; 
				$model->createdDate =  date('Y-m-d'); 
				$model->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
      			$model->save();
                return ['status' => true, 'message' => 'Feedback success'];              
            }
        }        
    }	
	public function actionSubcribeplan()    {   
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
				$plan = Plans::find()->where(['planId'=>$post['planId']])->one();
				$doctorId =0;
				$dieticianId=0;
				if(isset($post['status']) && !empty($post['status']))
				{
					$status =$post['status'];					
				}
				else
				{
					$status = "Un-Subcribed";
				}
				if(isset($post['clinicId']) && !empty($post['clinicId']))
				{
					$clinicId =$post['clinicId'];					
				}
				else
				{
					$clinicId = "";
				}
				if(isset($post['franchiesId']) && !empty($post['franchiesId'])){
					$franchiesId =$post['franchiesId'];					
				}
				else
				{
					$franchiesId = "";
				}
				if(!empty($plan->doctorId))
				{
					$doctorId = $plan->doctorId;					
				}
				else
				{
					if(!empty($clinicId))
					{
						$users = User::find()->leftjoin('doctors','doctors.userId=user.id')->where(['roleId'=>3,'doctors.clinicId'=>$clinicId,'doctors.Status'=>'Active'])->all();
						foreach($users as $key=>$value)
						{						
							$doctorplan = Userplans::find()->where(['doctorId'=>$value->id])->count();
							if(!empty($clinicId))
							{
								$doctorplan = 0;
							}
							if($doctorplan == 0)
							{
								$doctorId = $value->id;
							}
							else
							{
								$doctorplan = "select  doctorId,count(*) as total  FROM userplans where Status='Subcribed' GROUP BY doctorId ORDER BY count(*) ASC";
								$model = Userplans::findBySql($doctorplan)->asArray()->all();
								$doctorId = $model[0]['doctorId'];
							}
						}
					}
				}
				if(!empty($clinicId))
				{
					$dieticians = User::find()->leftjoin('dietician','dietician.userId=user.id')->where(['roleId'=>5,'dietician.clinicId'=>$clinicId,'dietician.Status'=>'Active'])->all();
					foreach($dieticians as $dkey=>$dvalue)
					{
						$dietplan = Userplans::find()->where(['dieticianId'=>$dvalue->id])->count();
						if(!empty($clinicId))
						{
							$dietplan = 0;
						}
						if($dietplan == 0)
						{
							$dieticianId = $dvalue->id;
						}
						else
						{
							$dietplan = "select  dieticianId,count(*) as total  FROM userplans where Status='Subcribed' GROUP BY dieticianId ORDER BY count(*) ASC";
							$model = Userplans::findBySql($dietplan)->asArray()->all();
							$dieticianId = $model[0]['dieticianId'];
						}
					}
				}
				$doctorplancount = Plandetails::find()->where(['planId'=>$post['planId'],'text'=>1])->count();
				$dietianplancount = Plandetails::find()->where(['planId'=>$post['planId'],'text'=>2])->count();
				if($doctorplancount == 0 && $plan->unlimdoctorcons == 0)
				{
					$doctorId = 0;
				}
				if($dietianplancount == 0 && $plan->unlimdiecticiancons == 0)
				{
					$dieticianId = 0;
				}
				$plan = Userplans::find()->where(['planId'=>$post['planId'],'access_token'=>$access_token])->andWhere(['!=','Status','Expired'])->one();
				if(empty($plan))
				{
					$model = new Userplans();
					$price = Plans::find()->where(['planId'=>$post['planId']])->one();
					$model->planId =  $post['planId'];
					$model->price =  $price->offerPrice;					
					$model->doctorId = $doctorId;
					if(empty($price->referralbonus))
					{
						$model->referralamount = 0;
					}
					else
					{
						$model->referralamount = ($price->offerPrice*$price->referralbonus)/100;
					}
					$Plandetails = Plandetails::find()->where(['planId'=>$post['planId']])->orderBy('plandetailId DESC')->one();
					$model->planExpiryDate =  date('Y-m-d',strtotime("+".$Plandetails->endday." day", strtotime(date('Y-m-d'))));
					$model->dieticianId = $dieticianId;
					$model->Status = $status;
					$model->clinicId = $franchiesId;
					$model->access_token =  $access_token; 
					$model->createdDate = date('Y-m-d'); 
					$model->updatedDate = date('Y-m-d'); ;
					$model->save();
					$id = $model->userPlanId;
				}
				else
				{
					$id = $plan->userPlanId;
				}
                return ['status' => true, 'message' => 'Subcribe success','id'=>$id];              
            }
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
				$access_token = str_replace('Bearer ','',$Authorization);
                $newmodel = Userplans::find()->where(['access_token' => str_replace('Bearer ','',$Authorization),'Status'=>'Subcribed'])->one();
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
					  $consultations =[];
					  $details = Plandetails::find()->select('day, endday')->where(['planId'=>$newmodel->planId])->distinct()->asArray()->all();
					  if($details != [])
					  {						 
								foreach($details as $x=>$v)
								{
									$ditems = [];
									$status = [];
									$statusnew = [];
									$consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
								    $consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));;
									$cdetails = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$newmodel->planId])->asArray()->all();
									if($cdetails != [])
									{
										foreach($cdetails as $dk=>$dv)
										{											
											$ditems[] = $dv['itemName'];											
										}	
									}	
									$doctorbookingslot = Slotbooking::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['status'=>'Completed','access_token'=>$access_token])->one();
									$dConsultation[] = 'Doctor Consultation';
									$statusnew['textname'] = $dConsultation;
									if(!empty($doctorbookingslot))
									{
										$statusnew['status'] = 'Completed';										
									}
									else
									{
										$statusnew['status'] = '';										
									}									
									$status[]= $statusnew;
									//print_r(date('Y-m-d',strtotime($consultations[$x]['day'])));
									$dieticanbookingslot = Dieticianslotbooking::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['status'=>'Completed','access_token'=>$access_token])->one();
									$DietConsultation[] = 'Doctor Consultation';
									$statusnew['textname'] = ['Diet Counselling'];
									if(!empty($dieticanbookingslot))
									{										
										$statusnew['status'] = 'Completed';										
									}
									else
									{
										$statusnew['status'] = '';
									}
									$status[]= $statusnew;
									$pathtests = Orders::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['bookingStatus'=>'Reports Generated','access_token'=>$access_token])->one();
									$statusnew['textname'] = $ditems;
									if(!empty($dieticanbookingslot))
									{										
										$statusnew['status'] = 'Reports Generated';
									}
									$status[]= $statusnew;
									$consultations[$x]['text'] = $ditems;
									$consultations[$x]['Status'] = $status;
								}							
						}			
				}
				$doctorname = User::find()->where(['id'=>$newmodel->doctorId])->one()->username;
				$dieticanName = "";
				if($newmodel->dieticianId)
				{
					$dieticanName = User::find()->where(['id'=>$newmodel->dieticianId])->one()->username;
				}
                return ['status' => true,'message' => 'Plans','PurchaseDate'=>$newmodel->createdDate,'doctorname'=>$doctorname,'dieticanName'=>$dieticanName,'inclusions' => $data,'consultations'=>$consultations]; 
            }  
	}

	public function actionUserplandetailsnew()	{
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
				
				$access_token = str_replace('Bearer ','',$Authorization);
                $newmodel = Userplans::find()->where(['access_token' => str_replace('Bearer ','',$Authorization),'Status'=>'Subcribed'])->one();
				$dieticanName = "";
				$doctorname = "";
				if($newmodel->dieticianId)
				{
					$dieticanName = User::find()->where(['id'=>$newmodel->dieticianId])->one()->username;
				}
				if($newmodel->doctorId)
				{
					$doctorname = User::find()->where(['id'=>$newmodel->doctorId])->one()->username;
				}
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
					  $consultations =[];
					  $event['booking'] = 1;
					  $event['status'] = 1;
					  $details = Plandetails::find()->select('day, endday')->where(['planId'=>$newmodel->planId])->distinct()->asArray()->all();
					  if($details != [])
					  {						 
						  foreach($details as $x=>$v)
						  {	
							 //$event['status'] = 1; 
							 $ditems=[];
							 $testsnew=[];
							 $status = "";
							 $date = date('Y-m-d');			             				
							 $startdate = date('Y-m-d',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
							 $enddate = date('Y-m-d',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));
							 $consultations[$x]['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
						     $consultations[$x]['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));
							 $doctortext = Plandetails::find()->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$newmodel->planId,'text'=>1])->one();
							 if(!empty($doctortext))
							 {						 
								 $doctorprofile = Doctors::find()->where(['userId'=>$newmodel->doctorId])->one();
								 $ditems['serviesname'] = "Doctor Consultation";
								 $doctorbookingslot = Slotbooking::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['!=','status','Cancel'])->andwhere(['access_token'=>$access_token])->one();
								 
								 if(!empty($doctorbookingslot))
								 {
									$ditems['status'] = $doctorbookingslot->status;									 
									$ditems['date'] = date('M d,Y',strtotime($doctorbookingslot->slotDate));
									$ditems['slotTime'] = $doctorbookingslot->slotTime;
									$ditems['bookday'] = date('l',strtotime($doctorbookingslot->slotDate));
									$ditems['bookingid'] = $doctorbookingslot->bookingId;
									if($doctorbookingslot->status == 'Completed')
									{
										$event['status'] = 0;
									}
									else
									{
										$event['status'] = 1;
									}
								 }
								 else
								 {		
									if($x == 0)
									{
										$event['status'] = 0;
									}
									$ditems['date'] = date('Y-m-d',strtotime($consultations[$x]['day']));
									$ditems['slotTime'] = "";
									$ditems['bookday'] = date('l',strtotime(date('Y-m-d',strtotime($consultations[$x]['day']))));
									$ditems['bookingid'] = 0;
									if(date('Y-m-d',strtotime($consultations[$x]['endday'])) < date("Y-m-d"))
									{
										$ditems['status'] = "Missed";
									}
									else
									{	
										if($event['booking'] == 1 && $event['status'] == 0)
										{ 
											$event['booking'] = 0;											
											$ditems['status'] = "Pending";										
										}
										else
										{
											$ditems['status'] = "";
										}										
									}
								 }								 
								 $ditems['doctorId'] = $doctorprofile->doctorId;
								 $ditems['type'] = "doctor";
								 $ditems['exp'] = $doctorprofile->experience;
								 $ditems['qualification'] = $doctorprofile->qualification;				
								 $ditems['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
								 $ditems['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));
								 $ditems['Tests'] = $testsnew;								 
								 $consultations[$x]['text'][] = $ditems;
								 
							 }
							 $dieticiantext = Plandetails::find()->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$newmodel->planId,'text'=>2])->one();
							 if(!empty($dieticiantext))
							 {
								 $status = "";
								 $doctorprofile = Dietician::find()->where(['userId'=>$newmodel->dieticianId])->one();
				
								 $ditems['serviesname'] = "Dietician Consultation";
								 $dieticanbookingslot = Dieticianslotbooking::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['!=','status','Cancel'])->andwhere(['access_token'=>$access_token])->one();
								 
								 if(!empty($dieticanbookingslot))
								 {
									$ditems['status'] = $dieticanbookingslot->status;
									if($dieticanbookingslot->status == "Completed")
									{
										$event['status'] = 0; 
									}
									else
									{
										$event['status'] = 1;
									}
									$ditems['date'] = date('M d,Y',strtotime($dieticanbookingslot->slotDate));
									$ditems['slotTime'] = $dieticanbookingslot->slotTime;
									$ditems['bookday'] = date('l',strtotime($dieticanbookingslot->slotDate));
									$ditems['bookingid'] = $dieticanbookingslot->bookingId;		
								 }
								 else
								 {
									$ditems['date'] = date('Y-m-d',strtotime($consultations[$x]['day']));
									$ditems['slotTime'] = "";									
									$ditems['bookday'] = date('l',strtotime(date('Y-m-d',strtotime($consultations[$x]['day']))));
									$ditems['bookingid'] = 0;
									if(date('Y-m-d',strtotime($consultations[$x]['endday'])) < date("Y-m-d"))
									{
										$ditems['status'] = "Missed";
									}
									else
									{
										//if($date >= $startdate && $event['booking'] == 1)
										if($event['booking'] == 1 && $event['status'] == 0)
										{ 
											$event['booking'] =0;
											$ditems['status'] = "Pending";
										}
										else
										{
											$ditems['status'] = "";
										}
									}
								 }
								 $ditems['doctorId'] = $doctorprofile->dieticianId;
								 $ditems['type'] = "dietician";
								 $ditems['exp'] = $doctorprofile->experience;
								 $ditems['qualification'] = $doctorprofile->qualification;
								 $ditems['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
								 $ditems['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));
								 $ditems['Tests'] = $testsnew;
								 $consultations[$x]['text'][] = $ditems;
							 }
							 
							 $pathtests = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$newmodel->planId,'test_type'=>'pathtests'])->asArray()->all();
							 if($pathtests != [])
							 {
								$status = "";
								$tests = Orders::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['access_token'=>$access_token,'test_type'=>'pathtests'])->andwhere(['!=','bookingStatus','Cancel'])->one();
								$ditems['serviesname'] = "Pathology Tests";
								
								if(!empty($tests))
								{
									$ditems['date'] = date('M d,Y',strtotime($tests->slotDate));;								
									$ditems['bookday'] = date('l',strtotime($tests->slotDate));
									$ditems['status'] = $tests->bookingStatus;
									$ditems['bookingid'] = $tests->orderId;
									if($tests->bookingStatus == 'Reports Generated')
									{
										$event['status'] = 0;
									}
									else
									{
										$event['status'] = 1;
									}
								}
								else
								{										
									$ditems['date'] = date('Y-m-d',strtotime($consultations[$x]['day']));
									$ditems['bookday'] = date('l',strtotime(date('Y-m-d',strtotime($consultations[$x]['day']))));
									$ditems['bookingid'] = 0;
									if(date('Y-m-d',strtotime($consultations[$x]['endday'])) < date("Y-m-d"))
									{
										$ditems['status'] = "Missed";
									}
									else
									{
										//print_r($event);exit;
										if($event['booking'] == 1 && $event['status'] == 0)
										{ 
											$event['booking'] =0;
											$ditems['status'] = "Pending";
										}
										else
										{
											$ditems['status'] = "";
										}
									}											
								}	
								$ditems['type'] = "pathtests";
								$ditems['slotTime'] = "";
								$ditems['exp'] = "";
								$ditems['qualification'] = "";
								$ditems['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
								$ditems['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));
								foreach($pathtests as $pathkey=>$pathvalue)
								{
									$testsnew[$pathkey]['testId'] = $pathvalue['itemId'];
									$testsnew[$pathkey]['testname'] = $pathvalue['itemName'];
									$testsnew[$pathkey]['price'] = $pathvalue['rate'];
								}
								$ditems['doctorId'] = 0;
								$ditems['Tests'] = $testsnew;																
								$consultations[$x]['text'][] = $ditems;
							 }
							 $othertests = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['day'=>$v['day'],'endday'=>$v['endday'],'planId'=>$newmodel->planId,'test_type'=>'others'])->asArray()->all();
							 if($othertests != [])
							 {
								$status = "";
								$otests = Orders::find()->where(['>=','slotDate',date('Y-m-d',strtotime($consultations[$x]['day']))])->andwhere(['access_token'=>$access_token,'test_type'=>'others'])->andwhere(['!=','bookingStatus','Cancel'])->one();
								$ditems['serviesname'] = "Blood Tests";
								
								if(!empty($otests))
								{
									$ditems['date'] = date('M d,Y',strtotime($otests->slotDate));
									$ditems['bookday'] = date('l',strtotime($otests->slotDate));
									$ditems['status'] = $otests->bookingStatus;
									$ditems['bookingid'] = $otests->orderId;
									if($tests->bookingStatus == 'Reports Generated')
									{
										$event['status'] = 0;
									}
									else
									{
										$event['status'] = 1;
									}
								}
								else
								{										
									$ditems['date'] = date('Y-m-d',strtotime($consultations[$x]['day']));
									$ditems['bookday'] = date('l',strtotime(date('Y-m-d',strtotime($consultations[$x]['day']))));
									$ditems['bookingid'] = 0;
									if(date('Y-m-d',strtotime($consultations[$x]['day'])) < date("Y-m-d"))
									{
										$ditems['status'] = "Missed";
									}
									else
									{
										if($event['booking'] == 1 && $event['status'] == 0)
										{ 
											//$event['booking'] =0;
											$ditems['status'] = "";
										}
										else
										{
											$ditems['status'] = "";
										}	
									}											
								}	
								$ditems['type'] = "normaltest";
								$ditems['slotTime'] = "";
								$ditems['exp'] = "";
								$ditems['qualification'] = "";
								$ditems['day'] = date('M d,Y',strtotime("+".$v['day']." day", strtotime($newmodel->createdDate)));
								$ditems['endday'] = date('M d,Y',strtotime("+".$v['endday']." day", strtotime($newmodel->createdDate)));
								foreach($othertests as $opathkey=>$opathvalue)
								{
									$testsnew[$opathkey]['testId'] = $opathvalue['itemId'];
									$testsnew[$opathkey]['testname'] = $opathvalue['itemName'];
									$testsnew[$opathkey]['price'] = $opathvalue['rate'];
								}
								$ditems['doctorId'] = 0;
								$ditems['Tests'] = $testsnew;																
								$consultations[$x]['text'][] = $ditems;
							 }
						  }							
					  }			
				}
				
                return ['status' => true,'message' => 'Plans','PurchaseDate'=>date('M d,Y',strtotime($newmodel->createdDate)),'doctorname'=>$doctorname,'dieticanName'=>$dieticanName,'inclusions' => $data,'consultations'=>$consultations]; 
            }  
	}	
	
	public function actionUpcomingevents()	{
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
				$access_token = str_replace('Bearer ','',$Authorization);
                $newmodel = Userplans::find()->where(['access_token' => str_replace('Bearer ','',$Authorization),'Status'=>'Subcribed'])->one();
				if(!empty($newmodel) && $newmodel->Status == 'Subcribed')
				{
					if($newmodel->planExpiryDate <= date('Y-m-d'))
					{
						$newmodel->Status = 'Expired';
						$newmodel->save();
					}
				$doctorname = User::find()->where(['id'=>$newmodel->doctorId])->one()->username;
				$dieticanName = "";
				if($newmodel->dieticianId)
				{
					$dieticanName = User::find()->where(['id'=>$newmodel->dieticianId])->one()->username;
				}
				$start = strtotime($newmodel->updatedDate);
				$end = strtotime(date('Y-m-d'));
				$days_between = ceil(abs($end - $start) / 86400);
				$plan = Plans::find()->where(['planId'=>$newmodel->planId])->one()->PlanName;
				$testarray = [];
				$upcomingdetailsarray = [];
				$event['doctorbooking'] = 1;
				$event['dietbooking'] = 1;
				$event['textbooking'] = 1;				
				$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['planId'=>$newmodel->planId])->distinct()->asArray()->all();
				$upcomingdetails['day'] = date('Y-m-d');
				$upcomingdetails['endday'] = date('Y-m-d');;
				$upcomingdetails['plandetailId'] = "0";				
				$upcomingdetails['planId'] = "0";
				$upcomingdetails['createdBy'] = "0";				
				$upcomingdetails['updatedBy'] = "0";
				$duration = date_diff(date_create($upcomingdetails['day']), date_create($upcomingdetails['endday']));
				$upcomingdetails['duration'] = $duration->days;
				$upcomingdetails['type'] = '';
				$upcomingdetails['doctorName'] = '';					
				$upcomingdetails['doctorId'] = 0;
				$upcomingdetails['exp'] = '';
				$upcomingdetails['qualification'] = '';
				if($upcomingdetailsarraydata != [])
				{					
					foreach($upcomingdetailsarraydata as $key=>$value)
					{						
						$diet = Plandetails::find()->where(['day'=>$value['day'],'endday'=>$value['endday'],'text'=>2])->andwhere(['planId'=>$newmodel->planId])->asArray()->count();
						$doctor = Plandetails::find()->where(['day'=>$value['day'],'endday'=>$value['endday'],'text'=>1])->andwhere(['planId'=>$newmodel->planId])->asArray()->count();
						$event = $this->eventchecking($value,$newmodel,$access_token,$doctorname,$dieticanName);							
						//print_r($event);exit;
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
						    $upcomingdetails = $this->upcomingdetails($value,$newmodel,$access_token,$event,$doctorname,$dieticanName);
							
							if($event['textbooking'] == 0)
							{
								$upcomingdetailsarray = $this->upcomingdetailsarray($value,$newmodel,$access_token,$event,$doctorname,$dieticanName);
							}
							break;
						}
						
					}
				}
				return ['status' => true,'message' => 'Plans','PurchaseDate'=>$newmodel->createdDate,'plan'=>$plan,'doctorname'=>$doctorname,'dieticanName'=>$dieticanName,'upcomingdetails'=>$upcomingdetails,'diagnosticbooking'=>true,'Tests'=>$upcomingdetailsarray]; 
		
			}
			else
			{
					return ['status' => false,'message' => 'Currenlty plan is not mapped']; 
          	}
		}   
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
			
			if(date('Y-m-d',strtotime($upcomingdetails['endday'])) >= date('Y-m-d')){
				//print_r(date('Y-m-d',strtotime($upcomingdetails['day'])));exit;
			//$completebooking = Slotbooking::find()->where(['access_token'=>$access_token])->andWhere(['Status'=>"Completed"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
			$completebooking = Slotbooking::find()->where(['access_token'=>$access_token])->andWhere(['Status'=>"Completed"])->andwhere(['>=','slotDate',date('Y-m-d',strtotime($upcomingdetails['day']))])->orderBy('bookingId DESC')->one();
			$doctor = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday'],'text'=>1])->andwhere(['planId'=>$newmodel->planId])->asArray()->count();
			if(!empty($completebooking) && $doctor > 0)
			{
				$doctorbooking = 1;	
			}
			//$dieticianbooking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andWhere(['Status'=>"Completed"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
		    $dieticianbooking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andWhere(['Status'=>"Completed"])->andwhere(['>=','slotDate',date('Y-m-d',strtotime($upcomingdetails['day']))])->orderBy('bookingId DESC')->one();
		  	$diet = Plandetails::find()->where(['day'=>$data['day'],'endday'=>$data['endday'],'text'=>2])->andwhere(['planId'=>$newmodel->planId])->asArray()->count();
			if(!empty($dieticianbooking) && $diet > 0)
			{
				$dietbooking = 1;
			}
			$orderitemarray = [];
			//$orderbookings = Orders::find()->where(['access_token'=>$access_token])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->andwhere(['bookingStatus'=>'Reports Generated'])->orderBy('orderId DESC')->all();
			$orderbookings = Orders::find()->where(['access_token'=>$access_token])->andwhere(['>=','slotDate',date('Y-m-d',strtotime($upcomingdetails['day']))])->andwhere(['bookingStatus'=>'Reports Generated'])->orderBy('orderId DESC')->all();
			
			if(!empty($orderbookings))
			{
				foreach($orderbookings as $orderkey=>$ordervalue)
				{
					$order = Orderitems::find()->where(['orderId'=>$ordervalue->orderId])->all();
					foreach($order as $ok=>$ov)
					{
						$orderitem = ItemDetails::find()->where(['itemId'=>$ov->itemId])->one();
						$orderitemarray[] = $ov->itemId;
					}
				}
			}
			$textarray[0] =1; 
			$textarray[1] = 2;
			$textarray = array_merge($textarray,$orderitemarray);
			$newtest = Plandetails::find()->select('item_details.*')->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['NOT IN','text',$textarray])->andwhere(['day'=>$data['day'],'endday'=>$data['endday'],'test_type'=>'pathtests'])->andwhere(['planId'=>$newmodel->planId])->count();
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
			$newtest = Plandetails::find()->where(['NOT IN','text',$textarray])->andwhere(['day'=>$data['day'],'endday'=>$data['endday']])->andwhere(['planId'=>$newmodel->planId])->all();
			if($newtest!= [])
			{
				$upcomingdetails['text'] =$newtest[0]['text']; 
				$items = $newtest;				
				for($i=0;$i<count($items);$i++)
				{
							$text = ItemDetails::find()->where(['itemId'=>$items[$i]['text'],'test_type'=>'pathtests'])->one();
							if(!empty($text))
							{
								$testarray['testId'] =  $items[$i]['text'];
								$testarray['testname'] =  $text->itemName;
								$testarray['price'] =  $text->rate;
								$upcomingdetailsarray[] = $testarray;	
							}
													
				}
			}
			//print_r($upcomingdetailsarray);exit;
			/*if($upcomingdetailsarray != [])
			{
						date_default_timezone_set("Asia/Calcutta");
						$query = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['slotDate'=>date('Y-m-d'),'itemId'=>$upcomingdetailsarray[0]['testId'],'orders.access_token'=>$access_token])->one();
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
			}*/
			$text = ItemDetails::find()->where(['itemId'=>$upcomingdetailsarray[0]['testId']])->one()->itemName;
		    $upcomingdetails['text'] = $text;
					
		}
		
		return $upcomingdetailsarray ;
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
			if($event['doctorbooking'] == 0)
		    {
				$upcomingdetails['type'] = 'doctor';
				$upcomingdetails['doctorName'] = $doctorname;					
				$doctorprofile = Doctors::find()->where(['userId'=>$newmodel->doctorId])->one();
				$upcomingdetails['doctorId'] = $doctorprofile->doctorId;
				$upcomingdetails['exp'] = $doctorprofile->experience;
				$upcomingdetails['qualification'] = $doctorprofile->qualification;		
				//$booking = Slotbooking::find()->where(['access_token'=>$access_token])->andWhere(['!=','Status',"Cancel"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
				$booking = Slotbooking::find()->where(['access_token'=>$access_token])->andwhere(['>=','slotDate',date('Y-m-d',strtotime($upcomingdetails['day']))])->andWhere(['!=','Status',"Cancel"])->orderBy('bookingId DESC')->one();
				$upcomingdetails['text'] = 'Doctor Consultation';
				if(!empty($booking))
				{
						$upcomingdetails['bookingid'] = $booking->bookingId;
						$upcomingdetails['slotDate'] = date('M d,Y',(strtotime($booking ->slotDate)));
						$upcomingdetails['slotTime'] = $booking ->slotTime;
				}
				else
				{
						$upcomingdetails['bookingid'] = 0;
						$upcomingdetails['slotDate'] = "";
						$upcomingdetails['slotTime'] = "";
			
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
					//$booking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andWhere(['!=','Status',"Cancel"])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
					$booking = Dieticianslotbooking::find()->where(['access_token'=>$access_token])->andwhere(['>=','slotDate',date('Y-m-d',strtotime($upcomingdetails['day']))])->andWhere(['!=','Status',"Cancel"])->orderBy('bookingId DESC')->one();
											
					$upcomingdetails['text'] = 'Dietician Consultation';
					if(!empty($booking))
					{
						$upcomingdetails['bookingid'] = $booking->bookingId;
						$upcomingdetails['slotDate'] = date('M d,Y',(strtotime($booking ->slotDate)));
						$upcomingdetails['slotTime'] = $booking ->slotTime;
					}
					else
					{
						$upcomingdetails['bookingid'] = 0;
						$upcomingdetails['slotDate'] = "";
						$upcomingdetails['slotTime'] = "";
					}
					$upcomingdetails['status'] = '';
			}
			elseif($event['textbooking'] == 0)
			{
				$upcomingdetails['type'] = 'pathtests';
				$upcomingdetails['doctorId'] = 0;
				$upcomingdetails['doctorName'] = '';
				$upcomingdetails['exp'] = '';
				$upcomingdetails['qualification'] = '';
				//$orderbooking = Orders::find()->where(['access_token'=>$access_token])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->andwhere(['!=','bookingStatus','Report Generated'])->orderBy('orderId DESC')->one();
				$orderbooking = Orders::find()->where(['access_token'=>$access_token])->andwhere(['>=','slotDate',date('Y-m-d',strtotime($upcomingdetails['day']))])->andwhere(['!=','bookingStatus','Report Generated'])->orderBy('orderId DESC')->one();
				//print_r(date('Y-m-d',strtotime($upcomingdetails['day'])));exit;
				if(empty($orderbooking))
				{
						$upcomingdetails['status'] = '';
						$upcomingdetails['bookingid'] = 0;
						$upcomingdetails['slotDate'] = "";
						$upcomingdetails['slotTime'] = "";
				}
				else
				{
						$upcomingdetails['status'] = 'Booked';
						$upcomingdetails['slotDate'] = date('M d,Y',(strtotime($orderbooking ->slotDate)));
						$upcomingdetails['slotTime'] = "";
						$upcomingdetails['bookingid'] = $orderbooking ->orderId;
				}
				$orderitemarray = [];
				$orderbookings = Orders::find()->where(['access_token'=>$access_token])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->andwhere(['bookingStatus'=>'Reports Generated'])->orderBy('orderId DESC')->all();
				if(!empty($orderbookings))
				{
					foreach($orderbookings as $orderkey=>$ordervalue)
					{
						$order = Orderitems::find()->where(['orderId'=>$ordervalue->orderId])->all();
						foreach($order as $ork=>$orv){
						$orderitem = ItemDetails::find()->where(['itemId'=>$orv->itemId,'test_type'=>'pathtests'])->one();
						if(!empty($orderitem))
						{
							$orderitemarray[] = $orderitem->itemId;
						}
						}
					}
				}
				$textarray[0] =1; 
				$textarray[1] = 2;
				$textarray = array_merge($textarray,$orderitemarray);
				//print_r($textarray);exit;
				$newtest = Plandetails::find()->leftjoin('item_details','item_details.itemId=plandetails.text')->where(['NOT IN','text',$textarray])->andwhere(['day'=>$data['day'],'endday'=>$data['endday'],'test_type'=>'pathtests'])->andwhere(['planId'=>$newmodel->planId])->one();
				if($newtest!= [])
				{
					$upcomingdetails['text'] =$newtest['text'];						
				}
				else
				{
					$upcomingdetails['status'] = '';
					$upcomingdetails['slotDate'] = '';
					$upcomingdetails['bookingid'] = 0;
			
				}
				$text = ItemDetails::find()->where(['itemId'=>$upcomingdetails['text']])->one();
				
		        $upcomingdetails['text'] = $text->itemName;
			}
		}
		return $upcomingdetails;
	}
	public static function actionBptrackchart()	{
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
				$records = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->all();
				foreach($records as $key=>$value)
				{
					$data[$key]['name'] = date('g:i A',strtotime($value->time));
					$data[$key]['pv'] = intval($value->SystolicValue).'/'.intval($value->DiastolicValue);
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
				$records = Bp::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] =  date("l",strtotime($value->createdDate));
						$recordnew = Bp::find()->where(['createdDate'=>$value->createdDate,'access_token'=>$access_token])->one();
						$data[$key]['pv'] = intval($recordnew->SystolicValue).'/'.intval($recordnew->DiastolicValue);;
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
					$recordnew = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->one();
					if(!empty($recordnew)){
					   $data[$i]['pv'] = intval($recordnew->SystolicValue).'/'.intval($recordnew->DiastolicValue);
					}
					else{
						$data[$i]['pv']  = "0";
					}	
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
						$recordnew = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->one();
						if(!empty($recordnew))
						{
							$data[$i]['pv'] = intval($recordnew->SystolicValue).'/'.intval($recordnew->DiastolicValue);
					    }
						else
						{
							$data[$i]['pv']  = "0";
						}
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data];
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
			$access_token = str_replace('Bearer ','',$Authorization);
			$dates = date('Y-m-d');
			if($get['type'] == 'day')
			{
				$records = Bp::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->all();
				foreach($records as $key=>$value)
				{
					$data[$key]['name'] = date('g:i A',strtotime($value->time));
					$data[$key]['pv'] = intval($value->SystolicValue).'/'.intval($value->DiastolicValue);
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
				$records = Bp::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$data[$key]['name'] =  date("l",strtotime($value->createdDate));
						$recordnew = Bp::find()->where(['createdDate'=>$value->createdDate,'access_token'=>$access_token])->one();
						$data[$key]['pv'] = intval($recordnew->SystolicValue).'/'.intval($recordnew->DiastolicValue);;
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
					$recordnew = Bp::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->one();
					if(!empty($recordnew)){
					   $data[$i]['pv'] = intval($recordnew->SystolicValue).'/'.intval($recordnew->DiastolicValue);
					}
					else{
						$data[$i]['pv']  = "0";
					}	
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
						$recordnew = Bp::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->one();
						if(!empty($recordnew))
						{
							$data[$i]['pv'] = intval($recordnew->SystolicValue).'/'.intval($recordnew->DiastolicValue);
					    }
						else
						{
							$data[$i]['pv']  = "0";
						}
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionBmitrackchart()	{
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
				$records = Bmivalues::find()->where(['createdDate'=>date('Y-m-d'),'access_token'=>$access_token])->all();
				foreach($records as $key=>$value)
				{
					$data[$key]['date'] = "DateTime(".date("Y",strtotime($value->createdDate)).' , '.date("m",strtotime($value->createdDate)).' , '.date("d",strtotime($value->createdDate)).")";
					$data[$key]['bmi'] = floatval($value->BMI);
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
				$records = Bmivalues::find()->select('createdDate')->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->distinct()->all();
				foreach($records as $key=>$value)
				{
						$recordnew = Bmivalues::find()->where(['createdDate'=>$value->createdDate,'access_token'=>$access_token])->one();
						$data[$key]['date'] = "DateTime(".date("Y",strtotime($value->createdDate)).' , '.date("m",strtotime($value->createdDate)).' , '.date("d",strtotime($value->createdDate)).")";
						$data[$key]['bmi'] = floatval($recordnew->BMI);
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
					$recordnew = Bmivalues::find()->where(['between', 'createdDate', $start_week, $end_week ])->andwhere(['access_token'=>$access_token])->one();
					if(!empty($recordnew)){
					   $data[$i]['pv'] = intval($recordnew->BMI);
					}
					else{
						$data[$i]['pv']  = 0;
					}	
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
						$recordnew = Bmivalues::find()->where(" Year( createdDate) = $currentYear ")->andwhere(" Month( createdDate) = $currentMonth ")->andwhere(['access_token'=>$access_token])->one();
						if(!empty($recordnew))
						{
							$data[$i]['pv'] = intval($recordnew->BMI);
					    }
						else
						{
							$data[$i]['pv']  = 0;
						}
						$date = date('Y-m-d',strtotime("+1 month", strtotime($date)));
				}
			}
			return ['status' => true, 'message' => 'Success','dates'=>$dates,'data'=>$data];
	   }
	}
	public static function actionNotifications()    {
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
				$date = date('Y-m-d');
                $query = Notifications::find()->where(['type'=>'common notifications'])->all();
                foreach($query as $key=>$value)
                {
                        $data[$key] = $value;
                }                
                return ['status' => true, 'message' => 'Notifications', 'data' => $data];             
            }
        } 
    }	
	public function actionSendnotifications()	{	
				$fcm = "cpMlqB9rQSmq8uN-kaNRCD:APA91bFL8vPaOy9NJP28Jo6iCKKder2S0mUc8SeVHbxD5m4rBmcHRgQ3hotNn-ho_aazMPzicF8drRWqDgiOwh6LaC-JTaXSx1VEEeMPtuUUV1uZKPOixbkwWuDaqKJQYrWpsx5u-PtU";
				$fixture_data = array('title' => "Sugar APP", 'body' => "Please Add Fasting Glucose Value","messageType"=>"Glucose Reading","senderId"=> "1022469224148");
                $arrayToSend = array('to' => $fcm, 'notification' => null, 'priority'=>'high', 'data' => $fixture_data);
                $noticationdata = json_encode($arrayToSend);
                $url = 'https://fcm.googleapis.com/fcm/send';
                $server_key = "AAAA7g_qNtQ:APA91bG6Omv9z1Muz2iYhClwL_BWqN0W7u6LEoZs00OHHVjAFq_TWACAuxudZytbIH1yswzK8McZHlIKyGMJ60ygcbYRSVxzR1n4BRSyka0yL1LssIqkMbZH0mKkcucQdgE4-UG9HQjE";
                $headers = array(
                                        'Content-Type:application/json',
                                        'Authorization:key='.$server_key
                                    );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $noticationdata);
                $result = json_decode(curl_exec($ch));                                                   
                return ['status' => true, 'message' => 'Success', 'data' => $result];              
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
				$plan = Userplans::find()->where(['access_token'=>$user->access_token,'Status'=>'Subcribed'])->one();
				$user = Doctors::find()->where(['userId'=>$plan->doctorId])->one()->doctorId;
				$slots = Slots::find()->where(['doctorId'=>$user,'slotDate'=>$_GET['date']])->all();
				
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
				
                return ['status' => true, 'message' => 'Slots', 'data' => $model];             
            }
        } 
    }
	public static function actionDslots()    {
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
				$plan = Userplans::find()->where(['access_token'=>$user->access_token,'Status'=>'Subcribed'])->one();
				$user = Dietician::find()->where(['userId'=>$plan->dieticianId])->one()->dieticianId;
				$slots = Dslots::find()->where(['dieticianId'=>$user,'slotDate'=>$_GET['date']])->all();				
                $model = [];
				if($slots != [])
				{
					
						foreach($slots as $key=>$value)
						{
							
							if($_GET['date'] == date('Y-m-d'))
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
				
                return ['status' => true, 'message' => 'Slots', 'data' => $model];             
                         
            }
        } 
    }
	public function actionSlotbooking()    {
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
				if(!empty($post['doctorId']))
				{
					$model = Slotbooking::find()->where(['slotDate'=>$post['slotDate'],'slotTime'=>$post['slotTime'],'doctorId'=>$post['doctorId'],'Status'=>'Booked'])->one();
				}				
				if(empty($model))
				{
					$model = new Slotbooking();
					$model->slotId =  $post['slotId']; 
					$model->doctorId = $post['doctorId'];
					$model->name = $user->username;
					$model->slotTime =  $post['slotTime']; 
					$model->slotDate = $post['slotDate'];
					$model->status = "Booked";
					$model->access_token =  $access_token; 
					$model->createdDate = date('Y-m-d'); 
					$model->updatedDate = date('Y-m-d'); 
					$doctor = Doctors::find()->where(['doctorId'=>$post['doctorId']])->one();
					$url = "https://agreements.apollohl.in/video-conference/meetings/createMeeting";
					$ch = curl_init($url);
					$dataU = array(
					'firstName' => $doctor->doctorName,
					'lastName' => '',
					'email' => $doctor->email,
					'mobile' => $doctor->mobilenumber,
					'client' => 'mobile',
					'date' => $post['slotDate'],
					'time' => $post['slotTime']
					);
					$payload = json_encode($dataU);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$resultDynamic = json_decode(curl_exec($ch));
					$model->videolink =$resultDynamic->data->meetingUrl;;
					$model->save();
					$status = 'Booked';
					$notification = $this->notificationforappointment($access_token,$doctor->doctorName,$post['slotDate'],$post['slotTime'],$status);					
			
				}
				else
				{
						return ['status' => false, 'message' => 'Already Slot Is booked Please check another slot']; 
				}
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->bookingId];              
            }
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
	        if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Slotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$model->slotId =  $post['slotId']; 
				$model->slotTime =  $post['slotTime']; 
				$model->slotDate = $post['slotDate'];
				$model->status = "Reshedule";
				$model->access_token =  $access_token; 
				$model->updatedDate = date('Y-m-d'); ;
				$model->save();	
				$doctor = Doctors::find()->where(['doctorId'=>$model->doctorId])->one();
				$status = 'Reshedule';
				$notification = $this->notificationforappointment($access_token,$doctor->doctorName,$post['slotDate'],$post['slotTime'],$status);					
			    return ['status' => true, 'message' => 'Slot Resheduled Successfully','bookingid'=>$model->bookingId];              
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
	        if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Slotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$model->status = "Cancel";
				$model->access_token =  $access_token; 
				$model->updatedDate = date('Y-m-d'); ;
				$model->save();	
				$doctor = Doctors::find()->where(['doctorId'=>$model->doctorId])->one();
				$status = 'Cancel';
				$notification = $this->notificationforappointment($access_token,$doctor->doctorName,$model->slotDate,$model->slotTime,$status);					
				return ['status' => true, 'message' => 'Slot Canceled Successfully','bookingid'=>$model->bookingId];              
            }
        }
    }
	public function notificationforappointment($access_token,$doctor,$date,$time,$status)
	{
		//print_r($access_token);exit;
		date_default_timezone_set("Asia/Calcutta");
		$data = [];
		$id = User::find()->where(['access_token'=>$access_token])->one();
		$value = Login::find()->where(['userId'=>$id->id])->one();;
		$time = date('H:i');
		$data['title'] = "Title";
		if($status == 'Booked')
		{
			$data['body'] = "Your appointment with ".$doctor." has been confirmed on ".$date." at ".$time.", let us know how you are doing";
		}
		elseif($status == 'Reshedule')
		{
			$data['body'] = "Your appointment with ".$doctor." has been Reshedule on ".$date." at ".$time.", let us know how you are doing";
		}
		elseif($status == 'Cancel')
		{
			$data['body'] = "Your appointment with ".$doctor." has been Cancel on ".$date." at ".$time.", let us know how you are doing";
		}
		$newmodel  = new Customernotifications();
		$newmodel->access_token = $access_token;
		$newmodel->message = $data['body'];
		$newmodel->time = $time;
		$newmodel->type = 'Appointment';
		$newmodel->createdDate = date('Y-m-d');
		$newmodel->updatedDate = date('Y-m-d');
		if(!$newmodel->save())
		{
			print_r($newmodel->errors);exit;
		}
		if(!empty($value))
		{
			$fcm_id[] = $value->gcm_id;
			$model = new Notifications();
			$send = $model->addUserFcm($data, $fcm_id);
		}
		
		//return $data;
	}	
	
	public function actionDieticianSlotbooking()    {
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
				$model = Dieticianslotbooking::find()->where(['slotDate'=>$post['slotDate'],'slotTime'=>$post['slotTime'],'dieticianId'=>$post['dieticianId'],'Status'=>'Booked'])->one();
				if(empty($model))
				{
					$model = new Dieticianslotbooking();
					$model->slotId =  $post['slotId']; 
					$model->dieticianId = $post['dieticianId'];
					$model->name = $user->username;
					$model->slotTime =  $post['slotTime']; 
					$model->slotDate = $post['slotDate'];
					$model->status = "Booked";
					$model->access_token =  $access_token; 
					$model->createdDate = date('Y-m-d'); 
					$model->updatedDate = date('Y-m-d'); ;
					$doctor = Dietician::find()->where(['dieticianId'=>$post['dieticianId']])->one();
					$url = "https://agreements.apollohl.in/video-conference/meetings/createMeeting";
					$ch = curl_init($url);
					$dataU = array(
					'firstName' => $doctor->dieticianName,
					'lastName' => '',
					'email' => $doctor->email,
					'mobile' => $doctor->mobilenumber,
					'client' => 'mobile',
					'date' => $post['slotDate'],
					'time' => $post['slotTime']
					);
					$payload = json_encode($dataU);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$resultDynamic = json_decode(curl_exec($ch));
					$model->videolink =$resultDynamic->data->meetingUrl;;
					$model->save();
					$status = 'Booked';
					$notification = $this->notificationforappointment($access_token,$doctor->dieticianName,$post['slotDate'],$post['slotTime'],$status);					
			
				}
				else
				{
						return ['status' => false, 'message' => 'Already Slot Is booked Please check another slot']; 
				}
                return ['status' => true, 'message' => 'Success','bookingid'=>$model->bookingId];              
            }
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
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Dieticianslotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$model->slotId =  $post['slotId']; 
				$model->slotTime =  $post['slotTime']; 
				$model->slotDate = $post['slotDate'];
				$model->status = "Reshedule";
				$model->access_token =  $access_token; 
				$model->updatedDate = date('Y-m-d'); ;
				$model->save();	
				$doctor = Dietician::find()->where(['dieticianId'=>$model->dieticianId])->one();
				$status = 'Reshedule';
				$notification = $this->notificationforappointment($access_token,$doctor->dieticianName,$post['slotDate'],$post['slotTime'],$status);					
			    return ['status' => true, 'message' => 'Slot Resheduled Successfully','bookingid'=>$model->bookingId];              
            }
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
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Dieticianslotbooking::find()->where(['bookingid'=>$post['bookingid']])->one();
				$model->status = "Cancel";
				$model->access_token =  $access_token; 
				$model->updatedDate = date('Y-m-d'); ;
				$model->save();			
                return ['status' => true, 'message' => 'Slot Canceled Successfully','bookingid'=>$model->bookingId];              
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
                $access_token = str_replace('Bearer ','',$Authorization);
				$model = Dieticianslotbooking::find()->where(['access_token'=>$user->access_token])->all();			
                $data = [];
				if($model != [])
				{
					foreach($model as $key=>$value)
					{
						$value['bookingId'] = strval($value['bookingId']);				
				        $value['slotId'] = strval($value['slotId']);
						$value['dieticianId'] = strval($value['dieticianId']);
						$doctor = Dietician::find()->where(['dieticianId'=>$value['dieticianId']])->one();
						$value['dieticianName'] = $doctor->dieticianName;
						$data[] = $value;
					}
				}
				return ['status' => true, 'message' => 'Success','model'=>$data];              
            }
        }
	public function actionDoctorprescription()	{
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
				$data = [];
				$prescription = Prescription::find()->where(['access_token'=>$user->access_token])->orderBy('createdDate DESC')->all();
				foreach($prescription as $key=>$value)
				{
					$data[$key]['prescription'] =  $value;
					$doctortests = DoctorTests::find()->where(['prescriptionId'=>$value->prescriptionId])->asArray()->all();
					$tests = [];
					foreach($doctortests as $k=>$v)
					{
						$item = ItemDetails::find()->where(['itemId'=>explode(',',$v['testname'])[0]])->one();
						$v['testId'] = strval($v['testId']);
						$v['prescriptionId'] = strval($v['prescriptionId']);
						$v['testname'] =explode(',',$v['testname'])[1];
						$v['price'] = $item->rate;
						$tests[$k] = $v;
					}
					$data[$key]['Tests'] = $tests;
					$medicines = Medicines::find()->where(['prescriptionId'=>$value->prescriptionId])->all();
					$pdflinks = Prescriptionpdfs::find()->where(['prescriptionId'=>$value['prescriptionId']])->one();
					$pdflink = Yii::$app->request->hostInfo."/ApolloSugar/".$pdflinks->fileName;					
					$data[$key]['Medicines'] = $medicines;
					$data[$key]['fileName'] = $pdflink;
				}
                return ['status' => true, 'message' => 'Prescription', 'data' => $data];             
            }
        }		
	}
	public function actionPrescriptionview()	{
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
				$data = [];
				if(!isset($_GET['id']) && empty($_GET['id']))
				{
					$prescription = Prescription::find()->where(['access_token'=>$user->access_token])->orderBy('createdDate DESC')->one();
				}
				else
				{
					$prescription = Prescription::find()->where(['prescriptionId'=>$_GET['id']])->one();
				}
				$prescription = Prescription::find()->where(['access_token'=>$user->access_token])->orderBy('createdDate DESC')->one();
				$data['prescription'] =  $prescription;
				$doctortests = DoctorTests::find()->where(['prescriptionId'=>$prescription->prescriptionId])->asArray()->all();
				$tests = [];
				foreach($doctortests as $k=>$v)
				{
						$item = ItemDetails::find()->where(['itemId'=>explode(',',$v['testname'])[0]])->one();
						$v['testname'] =explode(',',$v['testname'])[1];
						$v['price'] = $item->rate;
						$tests[$k] = $v;
				}
				$data['Tests'] = $tests;
				$medicines = Medicines::find()->where(['prescriptionId'=>$prescription->prescriptionId])->all();
				$pdflinks = Prescriptionpdfs::find()->where(['prescriptionId'=>$prescription['prescriptionId']])->one();
				$pdflink = "https://devapp.apollohl.in:8443/ApolloSugar/".$pdflinks->fileName;					
				$data['Medicines'] = $medicines;
				$data['fileName'] = $pdflink;
				}
                return ['status' => true, 'message' => 'Prescription', 'data' => $data];             
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
                $access_token = str_replace('Bearer ','',$Authorization);
				$data = [];
				$model = Slotbooking::find()->where(['access_token'=>$user->access_token])->asArray()->all();
				if($model != [])
				{
					foreach($model as $key=>$value)
					{
						$value['bookingId'] = strval($value['bookingId']);				
				        $value['slotId'] = strval($value['slotId']);
						$value['doctorId'] = strval($value['doctorId']);
						$doctor = Doctors::find()->where(['doctorId'=>$value['doctorId']])->one();
						$value['doctorName'] = $doctor->doctorName;
						$data[] = $value;
					}
				}
                return ['status' => true, 'message' => 'Success','doctorbookings'=>$data];              
            }
        }
    public function actionAddtocart()    {        
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
				Carttests::deleteAll(['access_token'=>str_replace('Bearer ','',$Authorization)]);
                if($post['Tests'] != [])
				{
					foreach($post['Tests'] as $key=>$value)
					{
						$cartmodel = new Carttests();
						$cartmodel->access_token = $user->access_token;
						$itemid = ItemDetails::find()->where(['itemId'=>$value['testId']])->one()->itemNewid;
						$cartmodel->itemId = $itemid;
						$cartmodel->itemName = $value['testname'];
						$cartmodel->price = $value['price'];
						$cartmodel->createdDate = date('Y-m-d');
						$cartmodel->updatedDate = date('Y-m-d');
						$cartmodel->save();
					}
				} 
                /*if($post['Medicines'] != [])
				{
					foreach($post['Medicines'] as $mkey=>$mvalue)
					{
						$medicinemodel = new Medicinecart();
						$medicinemodel->access_token = $user->access_token;
						$medicinemodel->medicineName = $mvalue['medicineName'];
						$medicinemodel->medicineId = $mvalue['medicineId'];
						$medicinemodel->createdDate = date('Y-m-d');
						$medicinemodel->updatedDate = date('Y-m-d');
						$medicinemodel->save();
					}
				} */
              return ['status' => true, 'message' => 'Success'];				
            }
        }
    public function actionRoasterslots()    {        
        $data = array();
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
				$get = Yii::$app->request->post();
				$tokenapiurl = "https://uatapp.apollohl.in/roster-management/api/auth/token";
				$tokench = curl_init($tokenapiurl);
				$tokendataU = array(
					'username'=>'google-home',
					'password'=>'password',
					'source'=>'Google',
					'grantType'=>'client_credentials',
				);
				$tokenpayload = json_encode ($tokendataU);
				curl_setopt($tokench, CURLOPT_POSTFIELDS, $tokenpayload);
				curl_setopt($tokench, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($tokench, CURLOPT_RETURNTRANSFER, true);
				$dynamic = json_decode(curl_exec($tokench));
				$token = $dynamic->token_type.' '.$dynamic->access_token;
                $url = "https://uatapp.apollohl.in/roster-management/api/slot/get-available-slots";
				$ch = curl_init($url);
				$dataU = array(
					'date'=>$get['date'],
					'noOfSlots'=>$get['noOfSlots'],
					'minMaxRadius'=>$get['minMaxRadius'],
					'lat'=>$get['lat'],
					'lng'=>$get['lng'],
				);
				$payload = json_encode ($dataU);
				$headr = array();
				$headr[] = 'Content-type: application/json';
				$headr[] = 'Authorization:'. $token;
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$res = json_decode(curl_exec($ch));
				$currentdate = date('Y-m-d');
        if($res->responseCode == 400)
        {
            $res->responseCode = 200;
        }
        $newdata = array();
        if($get['noOfSlots'] == '1')
        {
            $data = array_merge($res->morning,$res->noon); 
            $data = array_merge($data,$res->evening);
           $currenttime = (date('h:i A'));
            if($currentdate == $get['date'] && $currenttime <= "10:00 PM")
            {
               $timestamp = strtotime($currenttime) + 90*60;
               $time = date('h:i A', $timestamp);
               foreach($data as $key=>$value)
               {
                   if(date('H:i:s',strtotime($time)) < date('H:i:s',strtotime($value->slotTime)))
                   {
                       $newdata[] = $value;
                   }     
               }
            }
            elseif($currentdate != $get['date'])
            {
                $newdata= $data;
            }
            $res->morning = $newdata;
            $res->noon = null;
            $res->evening =null;
        }
        elseif($get['noOfSlots'] == '2')
        {
            $currenttime = (date('h:i A'));
            if($currentdate == $get['date'] && ($currenttime <= "10:00 PM"))
            {
               $timestamp = strtotime($currenttime) + 90*60;
               $time = date('h:i A', $timestamp);
               foreach($res->multiSlots as $key=>$value)
               {
                   if((date('H:i:s',strtotime($time)) < date('H:i:s',strtotime($value->slots[0]))) && (date('H:i:s',strtotime($time)) < date('H:i:s',strtotime($value->slots[1]))))
                   {
                       $newdata[] = $value;
                   }     
               }
               $res->multiSlots = $newdata;
            }
            elseif($currentdate == $get['date'])
            {
                $res->multiSlots = $newdata;
            }
        }
        $result = $res;
        return $result;          
            }
        }
	public function actionBooknow()    {        
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
                $items = Carttests::find()->where(['access_token'=>$user->access_token])->all();
				$userprofile = Userprofile::find()->where(['access_token'=>$user->access_token])->one();
				$packageitems = [];
				$data = [];
				foreach($items as $k=>$v)
				{
					$itemdetails = ItemDetails::find()->where(['itemNewid'=>$v['itemId']])->one();
					$data['itemId'] = $v['itemId'];
					$data['itemName'] = $itemdetails->itemName;
					$data['itemCode'] = $itemdetails->itemCode;
					$data['itemRate'] = $itemdetails->rate;
					$data['itemSubCategoryId'] =1;
					$data['itemType'] = 'Test';
					$data['discAmt'] = 0;
					$data['paidAmt'] = 0;
					$data['netAmt'] = $itemdetails->rate;
					$packageitems[] = $data;
				}
				$bookdata = [];
				$bookdata['lat'] = $post['lat'];
				$bookdata['lng'] = $post['lng'];
				$bookdata['lessThanYear'] = false;
				$bookdata['stateId'] = null;
				$bookdata['stateName'] = "";
				$bookdata['cityId'] = null;
				$bookdata['cityName'] = "";
				$bookdata['areaId'] = null;
				$bookdata['areaName'] = "";
				$bookdata['slotDate'] = date('d-m-Y',strtotime($post['slotDate']));
				$bookdata['slotTime'] = $post['slotTime'];
				$bookdata['name'] = $userprofile->firstName;
				$bookdata['mobile'] = $user->username;
				$bookdata['alternateMobile'] = $user->username;
				$bookdata['address'] = $post['address'];
				$bookdata['aadhar'] = '';
				$bookdata['gender'] = $userprofile->gender;
				$bookdata['ageInYears'] = $userprofile->age;
				$bookdata['passport'] = '';
				$bookdata['pincode'] = $post['pincode'];
				$bookdata['leadId'] ='141';
				$bookdata['isHomeCollection'] =true;
				$bookdata['homeCollectionItemName'] ="Home Collection Charge";
				$bookdata['homeCollectionCode'] ="OT007";
				$bookdata['homeCollectionAmt'] ="100";
				$bookdata['homeCollectionItemId'] ="2048";
				$bookdata['totalAmount'] ="1830";
				$bookdata['testAmount'] ="1730";
				$bookdata['location'] ="d8548516-de92-404c-b627-d5021f61b61f";
				if($userprofile->gender == 'Male'){
					$bookdata['title'] = 'Mr.';
				}
				else
				{
					$bookdata['title'] = 'Miss.';
				}
				$bookdata['bookingPackageRequest'] = $packageitems;
				$payload = json_encode($bookdata);
				$url = "https://uatapp.apollohl.in/lead-management-api/api/v1/home-collection/booking";
				$ch = curl_init($url);
				$headr = array();
				$headr[] = 'Content-type: application/json';
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$res = json_decode(curl_exec($ch));	
                if($res->status == "success")
				{			
					$ordermodel = new Orders();
					$ordermodel->access_token = $user->access_token;
					$ordermodel->prebookingId = $res->token;
					$ordermodel->bookingStatus = "Pending";
					$ordermodel->slotDate = date('Y-m-d',strtotime($post['slotDate']));
					$ordermodel->slotTime = $post['slotTime'];
					$ordermodel->createdDate = date('Y-m-d');
					if($ordermodel->save())
					{
							foreach($items as $key=>$value)
							{
								$orderitems = new Orderitems();
								$orderitems->orderId = $ordermodel->orderId;
								$orderitems->itemId = $value['itemId'];
								$ordermodel->access_token = $user->access_token;
								$orderitems->itemName = $value['itemName'];
								$orderitems->price = $value['price'];
								$orderitems->createdDate = date('Y-m-d');
								$orderitems->updatedDate = date('Y-m-d');
								$orderitems->save();
							}
					}
					Carttests::deleteAll(['access_token'=>$user->access_token]);
					return ['status' => true, 'message' => $res->message,'prebookingid'=>$res->token];	
				}
				else
				{
				       return ['status' => false, 'message' => $res->message];		
				}
            }
        }
    public function actionOrders()    {        
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
                $access_token = str_replace('Bearer ','',$Authorization);
				$data = [];
				$orders = Orders::find()->where(['access_token'=>$user->access_token])->asArray()->all();	
				if($orders != [])
				{
					foreach($orders as $key=>$value)
					{
						$value['orderId'] = strval($value['orderId']);
						$value['items'] = Orderitems::find()->where(['orderId'=>$value['orderId']])->all();
						$data[] = $value;
					}
				}
                return ['status' => true, 'message' => 'Success','model'=>$data];              
            }
        }
    public function actionRecommendedprograms()    {        
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
                $access_token = str_replace('Bearer ','',$Authorization);
				$data = [];
				$doctorplanId = Doctordrivenlinks::find()->where(['mobilenumber'=>$user->username,'status'=>'Success'])->all();
				if($doctorplanId != [])
				{
					foreach($doctorplanId as $dkey=>$dvalue)
					{						
						$plan = Plans::find()->where(['planId'=>$dvalue['programId']])->asArray()->one();
						$plan['programtype'] = 'Recommended';
						$data[] = $plan;
					}
				}
                return ['status' => true, 'message' => 'Success','recommendedprograms'=>$data];              
            }
        }
    public static function actionPatientsession()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $get = Yii::$app->request->get();
		date_default_timezone_set("Asia/Calcutta");
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
				$booking = Slotbooking::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				$session = Doctorslotsession::find()->where(['bookingId'=>$_GET['bookingId']])->one();
			   if(empty($session))
				{
					if(date('Y-m-d') == $booking->slotDate)
					{
							if(date('H:i') >= date('H:i',strtotime($booking->slotTime)))
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
									return ['status' => true, 'message' => 'Session Started','session'=>$session->session,'link'=>$booking->videolink.'/'.$user->username];
            				}
							{
										$time1 = new DateTime(date('H:i',strtotime($booking->slotTime)));
											$time2 = new DateTime(date('H:i'));
											$interval = $time2->diff($time1);
											if($interval->format('%h') > 0)
											{
												return ['status' => false, 'message' => 'To start the session we have '.$interval->format('%h hour %i minutes')];	
											}
											else
											{
												return ['status' => false, 'message' => 'To start the session we have '.$interval->format('%i minutes')];	
											}	
							}
					}
					else
					{
						return ['status' => false, 'message' => 'Your Slot is Not Today'];				
					}
				}
				else
				{
						return ['status' => true, 'message' => 'Session Already Started','session'=>$session->session,'link'=>$booking->videolink.'/'.$user->username];
				}
		}  
    }
	public static function actionPatientdieticiansession()    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();
        $get = Yii::$app->request->get();
		date_default_timezone_set("Asia/Calcutta");
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
				$booking = Dieticianslotbooking::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				$session = Dieticianslotsession::find()->where(['bookingId'=>$_GET['bookingId']])->one();
				if(empty($session))
				{
				if(date('Y-m-d') == $booking->slotDate)
				{
					if(date('H:i') >= date('H:i',strtotime($booking->slotTime)))
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
							return ['status' => true, 'message' => 'Session Started','session'=>$session->session,'link'=>$booking->videolink.'/'.$user->username];
					}											
					{
						$time1 = new DateTime(date('H:i',strtotime($booking->slotTime)));
						$time2 = new DateTime(date('H:i'));
						$interval = $time2->diff($time1);
						if($interval->format('%h') > 0)
						{
							return ['status' => false, 'message' => 'To start the session we have '.$interval->format('%h hour %i minutes')];	
						}
						else
						{
							return ['status' => false, 'message' => 'To start the session we have '.$interval->format('%i minutes')];	
						}
					}
				}
				else
				{
					return ['status' => false, 'message' => 'Your Slot is Not Today'];				
				}	
			    }
			    else
				{
					return ['status' => true, 'message' => 'Session Already Started','session'=>$session->session,'link'=>$booking->videolink.'/'.$user->username];
				}
		}  
    }
	
	public function actionFranchiesSendOtp()    {       
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
			if (empty($model->mobilenumber)) 
            {
                return ['status' => false, 'message' => 'Mobile number is required'];
            } 
            else
            {
 	    	    $user = User::find()->where(['mobilenumber'=>$model->mobilenumber,'roleId'=>8])->andWhere(['=', 'status', 10])->one(); 
				if(empty($user))
				{
					return ['status' => false, 'message' => 'Mobile number not registered'];
				}
				else
				{
					if($model->mobilenumber == '9177680088' ||  $model->mobilenumber == '9640746438' || $model->mobilenumber =='9848452600')
					{
						$otp = "123456";
						$user->otp_number = $otp;
						$user->save();
						$data['id'] = $user->id;
						$data['username'] = $user->username;
						$data['otp_number'] = $otp;
						return ['status' => true, 'message' => 'OTP Sent to Your Mobile Number','otp' => $otp];							
					}
					else
					{
						$otp = "123456";
						
						
				$message = "OTP ".$otp." to login to ".$model->mobilenumber.", Apollo Clinic ";
				$url = 'http://www.smsjust.com/sms/user/urlsms.php?username=apollohealth&pass=dM76$Bc-&senderid=APOCLN&dest_mobileno='.$model->mobilenumber.'&message='.urlencode($message).'&msgtype=TXT&response=Y';
				$crl = curl_init();
				//curl_setopt($crl, CURLOPT_URL, $url);
				//curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
				//curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
               //$res = curl_exec($crl);
					$res = 1;
				   if($res) 
					{
						
							$user->otp_number = $otp;
							$user->save();
							return ['status' => true, 'message' => 'OTP Sent to your mobile number','otp' => $otp];
					
					}
					else
					 {
						return ['status' => false, 'message' => 'OTP not Sent to your mobile number.'];
					 }
					}
					
 		        
				}
                
		   }
		
            }
        
    }    
    public function actionFranchiesValidateOtp() {
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
            if (empty($model->mobilenumber)) 
            {
                return ['status' => false, 'message' => 'Mobile number is required'];
            } 
            if (empty($model->otp_number)) 
            {
                return ['status' => false, 'message' => 'Otp number is required'];
            }
            $user = User::find()
                ->where(['mobilenumber' => $model->mobilenumber])              
                ->orderBy(['id'=> SORT_DESC])
                ->one(); 
            if (!empty($user)) 
            {
                if ($user->otp_number == $model->otp_number) 
                {
                    $user->otp_number = null;
                    $user->status = User::STATUS_ACTIVE;
                    $user->save();
					$login = Login::find()->where(['userId'=>$user->id])->one();
					if(empty($login))
					{
						$newmodel = new Login();
						$newmodel->userId = $user->id;
						$newmodel->gcm_id = $model->gcm_id;
						$newmodel->device_info = $model->device_info;
						$newmodel->app_info = $model->app_info;
						$newmodel->createdDate = date('Y-m-d H:i;s');
						$newmodel->updatedDate = date('Y-m-d H:i;s');           
						$newmodel->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
						$newmodel->save();
					}
					else
					{
							$login->gcm_id = $model->gcm_id;
							$login->save();
					}
					
                    return ['status' => true, 'message' => 'Otp validated successfully','data'=>$user];
                }
                else 
				{
                    return ['status' => false, 'message' => 'OTP is not valid'];
                }
            }
            else
            {
                return ['status' => false, 'message' => 'Mobile number is invalid'];
            }
       }
    }
   
}