<?php

namespace backend\modules\packages\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
/**
 * This is the model class for table "plans".
 *
 * @property int $planId
 * @property string|null $PlanName
 * @property string|null $aliasName
 * @property string|null $tenture
 * @property float|null $Price
 * @property float|null $offerPrice
 * @property int|null $discount
 * @property int|null $cityId
 * @property string|null $cityName
 * @property int|null $locationId
 * @property string|null $locationName
 * @property string $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Plans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plans';
    }
	public $cities;
	public $locations;
	public $inclusions;
	public $items;
	public $item;
	public $newitems;
	public $doctors;
	public $coaches;
	public $dieticans;
	public $itemsnew;
	public $hba1c;
	public $gender;
	public $age;
	public $agevalue;
	public $hba1cvalue;
	public $physicalactivity;
	public $approval;
	public $diagnosticamount;
	public $diagnosticstests;
    /**
	
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Price', 'offerPrice'], 'number'],
            [['discount', 'createdBy', 'updatedBy','duration'], 'integer'],
            [['Status','PlanName','duration','inclusions'], 'required'],
            [['Status'], 'string'],
            [['createdDate', 'updatedDate','inclusions','items','StartDate',
			'item','newitems','doctorId','dieticanId','coachId','dieticans',
			'coaches','dieticans','itemsnew','doctordriven','general','programapplicable',
			'hba1c','hba1cvalue','gender','age','agevalue','physicalactivity','referralbonus','unlimdoctorcons',
			'unlimdiecticiancons','approval','diagnosticamount','diagnosticstests'], 'safe'],
			['discount', 'integer', 'min' => 0, 'max' => 100],
			['referralbonus', 'integer', 'min' => 0, 'max' => 100],
            [['PlanName', 'aliasName', 'tenture',  'locationName', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'planId' => 'Plan ID',
			'doctorId' => 'Doctor',
			'coachId' => 'Coach',
			'dieticanId' => 'Dietician',
            'PlanName' => 'Program Name',
            'aliasName' => 'Description',
            'duration' => 'Duration (In Days)',
			'inclusions'=>'Services',
			'newitems'=>'Inclusions',
            'Price' => 'Price',
            'offerPrice' => 'Offer Price',
            'discount' => 'Discount(%)',  
		    'referralbonus'=>'Referral Bonus(%)',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
			'programapplicable'=>'Program Applicable To',
            'ipAddress' => 'Ip Address',
			'doctordriven'=>'Doctor Driven',
			'unlimdiecticiancons'=>'Unlimited Dietican Consultations',
			'unlimdoctorcons'=>'Unlimited Doctor Consultations'
        ];
    }
	
	public function beforeSave($insert) {
		//echo 'hi';exit;
       
        if ($this->isNewRecord) 
        {			 
			
            $this->createdDate = date('Y-m-d H:i;s');
            $this->updatedDate = date('Y-m-d H:i;s');
            $this->createdBy = Yii::$app->user->id;
            $this->updatedBy = Yii::$app->user->id;
			$this->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
			
		
	   } 
        else 
        {
            $this->updatedDate = date('Y-m-d H:i;s');            
            $this->updatedBy = Yii::$app->user->id;
			$this->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
       
        }
       // print_r($this->cityName);exit;
        return parent::beforeSave($insert);
    }
	public static function getPlans()
    {
        $model = Plans::find()->where(['Status'=>'Active'])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['planId']] = $value['PlanName'];
        }
        return $data;
    }
}
