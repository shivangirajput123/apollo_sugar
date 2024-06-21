<?php

namespace backend\modules\article\models;

use Yii;
use backend\modules\common\models\Categories;
/**
 * This is the model class for table "articles".
 *
 * @property int|null $articleId
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
class Articles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
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
            [['Status','categoryId','title','Url'], 'required'],
			[['Url'],'url'],
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
            'articleId' => 'Article ID',
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
}
