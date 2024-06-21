<?php

namespace frontend\modules\react\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\LoginForm;
use backend\modules\roles\models\Roles;
use frontend\models\Userprofile;
use backend\models\ChangePasswordForm;
use backend\modules\users\models\Doctors;
use backend\modules\users\models\Dietician;
/**
 * Default controller for the `quiz` module
 */
class LoginController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
		$model = new LoginForm();
		$data = array();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Content-Type");
        if ($model->load(\Yii::$app->request->post(), '') ){			
			if (empty($model->username)) 
            {
                return ['status' => false, 'message' => 'Username is required'];
            } 
			if (empty($model->password)) 
            {
                return ['status' => false, 'message' => 'Passworc is required'];
            }
           if( $model->login())
		   {
			   if($model->username != 'admin@gmail.com')
			   {
					$user = User::find()->where(['email'=>$model->username])->asArray()->one();
					if($user['roleId'] == 3)
					{
						$profile = Doctors::find()->where(['userId'=>$user['id']])->one();
						$user['gender'] = $profile->gender;
					}
					elseif($user['roleId'] == 5)
					{
						$profile = Dietician::find()->where(['userId'=>$user['id']])->one();
						$user['gender'] = $profile->gender;
					}
					elseif($user['roleId'] == 8 || $user['roleId'] == 7)
					{
						$user['gender'] = "";
					}
					return ['status' => true, 'message' => 'Login Successfully','data' => [$user]];
			   }
			   else
			   {
				   return ['status' => false, 'message' => 'Incorrect username or password'];
			   }
		   }
		   else
		   {
			   return ['status' => false, 'message' => 'Incorrect username or password'];
		   }
        } 
		else 
		{
           return ['status' => false, 'message' => 'Data not loaded.'];
        }	
    }
	
	public function actionSendOtp()
    {
       
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
		//$model->email = $post['email'];
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
            if (empty($post['email'])) 
            {
                return ['status' => false, 'message' => 'Email is required'];
            } 
            else
            {
             // $otp = $this->randomNumber(6); 
              $otp = '123456'; 			 
              $user = User::find()->where(['email'=>$post['email']])->andWhere(['=', 'status', 10])->one();
              $message = "Your OTP is " . $otp . " for Apollo diagnostics login.";
              $res = 1;
              if($res) 
              {
                    if(empty($user))
                    {
                       return ['status' => false, 'message' => 'Invalid mail please check once'];
                    }
                    else
                    {
                        $user->otp_number = $otp;
                        $user->save();
                        return ['status' => true, 'message' => 'OTP Sent to your Email','otp' => $otp];
                    } 
                }
                else
                {
                    return ['status' => false, 'message' => 'OTP not Sent to your Email.'];
                }
            }
        }
    }
    
    public function actionValidateOtp() {
        $model = new LoginForm();
        $data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
            if (empty($post['email'])) 
            {
                return ['status' => false, 'message' => 'Email number is required'];
            } 
            if (empty($post['otp_number'])) 
            {
                return ['status' => false, 'message' => 'Otp number is required'];
            }
            $user = User::find()
                ->where(['email' => $post['email']])              
                ->orderBy(['id'=> SORT_DESC])
                ->one(); 
            if (!empty($user)) 
            {
                if ($user->otp_number == $model->otp_number) 
                {
                    $user->otp_number = null;
                    $user->status = User::STATUS_ACTIVE;
                    $user->save();
					return ['status' => true, 'message' => 'Otp validated successfully'];
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
	
	public function actionChangePassword()
    {    	
    	$model = new ChangePasswordForm();  
		$data = array();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		$post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post(), '') ) 
        {
            if (empty($post['email'])) 
            {
                return ['status' => false, 'message' => 'Email number is required'];
            } 
            if (empty($post['password'])) 
            {
                return ['status' => false, 'message' => 'Password is required'];
            }
            $user = User::find()
                ->where(['email' => $post['email']])              
                ->orderBy(['id'=> SORT_DESC])
                ->one(); 
            if (!empty($user)) 
            {
				$model->password = $post['password'];
                if ($model->resetPassword($user->id)) 
                {
                   	return ['status' => true, 'message' => 'Password Reset successfully'];
                }
                else 
				{
                    return ['status' => false, 'message' => 'Password Not Reset'];
                }
            }
            else
            {
                return ['status' => false, 'message' => 'Email is invalid'];
            }            
        }
    }
    
}
