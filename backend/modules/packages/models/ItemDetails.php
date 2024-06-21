<?php

namespace backend\modules\packages\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
/**
 * This is the model class for table "item_details".
 *
 * @property int $itemId
 * @property string|null $itemName
 * @property string|null $itemCode
 * @property string|null $aliasName
 * @property float|null $rate
 * @property float|null $offerPrice
 * @property int $discount
 * @property int|null $cityId
 * @property string|null $cityName
 * @property int|null $locationId
 * @property string|null $locationName
 * @property string|null $itemDescription
 * @property string $Status
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class ItemDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_details';
    }
	public $cities;
	public $locations;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rate', 'offerPrice','discount'], 'number'],
            [['itemName', 'Status', 'rate'], 'required'],
        //    [['discount', 'cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['itemDescription', 'Status'], 'string'],
            [['createdDate', 'updatedDate','discount','test_type'], 'safe'],
            [['itemName', 'itemCode', 'aliasName'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
			['discount', 'integer', 'min' => 0, 'max' => 100],
			['rate', 'integer', 'min' => 0, 'max' => 100000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'itemId' => 'Item ID',
            'itemName' => 'Item Name',
            'itemCode' => 'Item Code',
            'aliasName' => 'Alias Name',
            'rate' => 'Our Rate',
            'offerPrice' => 'Offer Price',
            'discount' => 'Discount',
            'cityId' => 'City',
            'cityName' => 'City Name',
            'locationId' => 'Location',
            'locationName' => 'Location Name',
            'itemDescription' => 'Item Description',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
	
	public function beforeSave($insert) {
		//echo 'hi';exit;
      /*  $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->locationId])->one();
        $this->cityName = $city->title;
		$this->locationName = $location->title;*/
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
	public static function getItems()
    {
        $model = ItemDetails::find()->where(['Status'=>'Active'])->orderBy('itemId DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['itemId']] = $value['itemName'];
        }
        return $data;
    }
}
