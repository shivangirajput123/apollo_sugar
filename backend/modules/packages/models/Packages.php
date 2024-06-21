<?php

namespace backend\modules\packages\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use backend\modules\packages\models\Packageitems;
use backend\modules\packages\models\Planitems;
/**
 * This is the model class for table "packages".
 *
 * @property int $packageId
 * @property string|null $packageName
 * @property string|null $packageDes
 * @property string|null $Status
 * @property int|null $cityId
 * @property string|null $cityName
 * @property int|null $locationId
 * @property string|null $LocationName
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Packages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages';
    }
	public $cities;
	public $locations;
	public $inclusions;
	public $items;
	public $item;
	public $newitems;
	public $itemsnew;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['packageDes', 'Status'], 'string'],
            [['cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['createdDate', 'updatedDate','inclusions','items','item','newitems','itemsnew'], 'safe'],
			[['packageName','inclusions'],'required'],
            [['packageName', 'cityName', 'LocationName', 'ipAddress'], 'string', 'max' => 200],
			[['packageName'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'packageId' => 'Package ID',
            'packageName' => 'Package Name',
            'packageDes' => 'Package Des',
            'Status' => 'Status',
            'cityId' => 'City',
            'cityName' => 'City Name',
            'locationId' => 'Location',
            'LocationName' => 'Location Name',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
	public function beforeSave($insert) {
		//echo 'hi';exit;
   /*     $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->locationId])->one();
        $this->cityName = $city->title;
		$this->LocationName = $location->title;*/
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
	
	public static function getItemsBKP()
    {
        $model = Packages::find()->where(['Status'=>'Active'])->orderBy('packageId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
			$items = '';
			
			$packageitems = Packageitems::find()->where(['packageId'=>$value['packageId']])->asArray()->all();
			$items = '<br> <ol type="a">';
			foreach($packageitems as $k=>$v)
			{
				$items .= '<li>'.$v['itemName'].'</li>';
			}
			$items .= '</ol>';
			//echo $items;exit;
            $data[$value['packageId']] = ++$key.'. '.$value['packageName'].' '.html_entity_decode($items);
        }
        return $data;
    }
	
	public static function getItems()
    {
        $model = Packages::find()->where(['Status'=>'Active'])->orderBy('packageId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['packageId']] = $value['packageName'];
        }
        return $data;
    }
	
	public static function getPackageItmes($id)
    {
		$data = array();
		$newarray = array();
		foreach($id as $k=>$v)
		{
			$model = Packageitems::find()->where(['packageId'=>$v])->asArray()->all();
            foreach($model as $key=>$value)
			{
				$data[$key]['id'] = $value['itemId'];
				$data[$key]['name'] = $value['itemName'].' - '.$value['PackageName']; 
				//$data[$key][$value['itemId']] = $value['itemName'].' - '.$value['PackageName'];
				array_push($newarray,$data[$key]);				
			}
		}
	//	print_r($newarray);exit;
        return $newarray;
    }
	
	public static function getItemsNew($id)
    {
		$data = array();		
		$model = Planitems::find()->where(['planId'=>$id])->asArray()->all();
        foreach($model as $key=>$value)
			{			 
				$data[$value['itemId']] = $value['ItemName'];								
			}
		//print_r($data);exit;
        return $data;
    }
	
	public static function getItemsNewBYId($id)
    {
		$data = array();		
		$model = Planitems::find()->where(['planId'=>$id])->asArray()->all();
        foreach($model as $key=>$value)
			{			 
				$data[$key] = $value['itemId'];								
			}
		//print_r($data);exit;
        return $data;
    }
}
