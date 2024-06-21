<?php

namespace backend\modules\common\models;

use Yii;
use backend\modules\common\models\Categories;
/**
 * This is the model class for table "excercise".
 *
 * @property int $ExcerciseId
 * @property string|null $categoryName
 * @property int|null $categoryId
 * @property string|null $title
 * @property string|null $Description
 * @property string|null $Url
 * @property string|null $file
 * @property string $Status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Excercise extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'excercise';
    }
	public $categories;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryId', 'createdBy', 'updatedBy'], 'integer'],
            [['Description', 'Status'], 'string'],
            [['Status','categoryId','title'], 'required'],
            [['createdDate', 'updatedDate','categories'], 'safe'],
            [['categoryName', 'title', 'Url', 'file', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ExcerciseId' => 'Excercise ID',
            'categoryName' => 'Category Name',
            'categoryId' => 'Category',
            'title' => 'Title',
            'Description' => 'Description',
            'Url' => 'Url',
            'file' => 'File',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
	public function beforeSave($insert) {
        $category = Categories::find()->where(['categoryId'=>$this->categoryId])->one();
        $this->categoryName = $category->categoryName;
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
	
	 public static function excerciselist()
    {
        $model = Excercise::find()->where(['Status'=>'Active'])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['ExcerciseId']] = $value['title'];
        }
        return $data;
    }
}
