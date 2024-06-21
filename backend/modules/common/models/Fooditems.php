<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "fooditems".
 *
 * @property int $itemId
 * @property string|null $itemName
 * @property string|null $itemDescription
 * @property string $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property int|null $ipAddress
 */
class Fooditems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fooditems';
    }
	public $calories;
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemDescription', 'Status'], 'string'],
            [['Status','itemName'], 'required'],
			['itemName','unique'],
            [['createdBy', 'updatedBy', 'ipAddress'], 'integer'],
            [['createdDate', 'updatedDate','calories'], 'safe'],
            [['itemName'], 'string', 'max' => 200],
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
        $model = Fooditems::find()->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['itemId']] = $value['itemName'];
        }
        return $data;
    }
}
