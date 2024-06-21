<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\UserToken;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $otp_number;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],            
            ['password', 'string', 'min' => 6],
            ['otp_number', 'string', 'min' => 6],
            ['otp_number', 'safe'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    
    public function signupmobile($otp = Null)
    {
        if (!$this->validate()) {
          //  print_r($this->errors);exit;
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->otp_number = $otp;
        $user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
        $user->access_token = Yii::$app->getSecurity()->generateRandomString(40);
        $user->password_hash = Yii::$app->getSecurity()->generateRandomString(32);
        $user->status = User::STATUS_INACTIVE;
        
        if (!$user->save()) 
        {
           throw new Exception("User couldn't be  saved");
        }
        $token = UserToken::create(
                    $user->id, UserToken::TYPE_ACTIVATION, time()
        );
        return $user;
    }

    

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
