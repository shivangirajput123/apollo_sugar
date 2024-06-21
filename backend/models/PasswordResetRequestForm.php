<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
		//ob_start();
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
			//print_r($user->generatePasswordResetToken()); exit;
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
	 $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
        			$to = $this->email;
            $subject = 'Password reset for ' . Yii::$app->name;
			   $mailContent = '
               To reset your password, visit the following link: <a href="'.$resetLink.'">'.$resetLink.'</a>';
               
         
          //$message =  $user; 
         
         $header = "From:web@theapolloclinic.net\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$mailContent,$header);
         
       
		 
		 return $retval;
		


    }
}
