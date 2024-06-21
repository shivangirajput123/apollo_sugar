<?php

namespace backend\modules\common\models;

use Yii;
use backend\modules\common\models\City;
/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property int $cid city id
 * @property string $title
 * @property string $email
 * @property string $address
 * @property string|null $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
   
    public $cities;
    public static function tableName()
    {
        return 'location';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cid', 'title', 'email', 'address'], 'required'],
            [['cid', 'createdBy', 'updatedBy'], 'integer'],
            [['Status'], 'string'],
            [['createdDate', 'updatedDate','cities','cityName'], 'safe'],
            [['title', 'email'], 'string', 'max' => 155],
            [['address'], 'string', 'max' => 255],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'City',
            'title' => 'Title',
            'email' => 'Email',
            'address' => 'Address',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }

    public function beforeSave($insert) {
        $city = City::find()->where(['id'=>$this->cid])->one();
        $this->cityName = $city->title;
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
    public static function getLocations($id)
    {
        $model = Location::find()->where(['cid' => $id,'Status'=>'Active'])->orderBy('id DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$key]['id'] = $value['id'];
            $data[$key]['name'] = $value['title'];
        }
        return $data;
    }
	public static function getLocationsByID($id)
    {
        $model = Location::find()->where(['cid' => $id,'Status'=>'Active'])->orderBy('id DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['id']] = $value['title'];
        }
        return $data;
    }
}
