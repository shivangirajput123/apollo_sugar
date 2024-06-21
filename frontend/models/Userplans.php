<?php
namespace frontend\models;
use Yii;
/**
 * This is the model class for table "userplans".
 *
 * @property int $userPlanId
 * @property string|null $access_token
 * @property int|null $planId
 * @property string $createdDate
 * @property string $updatedDate
 */
class Userplans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userplans';
    }

    /**
     * {@inheritdoc}
     */
	public $doctors;
	public $dieticians;
    public function rules()
    {
        return [
            [['planId'], 'integer'],
           // [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate','doctorId','Status',
			'price','referralamount','planExpiryDate','doctors',
			'dieticians','dieticianId','clinicId','txnId'], 'safe'],
            [['access_token'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userPlanId' => 'User Plan ID',
            'access_token' => 'Access Token',
            'planId' => 'Plan ID',
			'doctorId' => 'Doctor',
			'dieticianId' => 'Dietician',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
