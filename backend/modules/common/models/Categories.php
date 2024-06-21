<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int|null $categoryId
 * @property string|null $categoryName
 * @property string|null $categoryDes
 * @property string $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdBy', 'updatedBy'], 'integer'],
            [['categoryDes', 'Status'], 'string'],
			[['categoryName','Status','type'], 'required'],
			[['categoryName'], 'unique'],
          //  [['Status', 'createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate','type'], 'safe'],
            [['categoryName'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Category ID',
            'categoryName' => 'Name',
            'categoryDes' => 'Description',
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
	
	public static function getCategories($type)
    {
        $model = Categories::find()->where(['Status'=>'Active','type'=>$type])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['categoryId']] = $value['categoryName'];
        }
        return $data;
    }
}
