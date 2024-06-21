<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "labtests".
 *
 * @property int $labTestId
 * @property string|null $testName
 * @property string|null $status
 * @property string|null $createdDate
 * @property string|null $description
 * @property string|null $updatedDate
 * @property int|null $updatedBy
 * @property int|null $createdBy
 * @property string|null $ipAddress
 */
class Labtests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'labtests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'string'],
            [['createdDate', 'updatedDate'], 'safe'],
			[['testName', 'status'], 'required'],
            [['updatedBy', 'createdBy'], 'integer'],
            [['testName', 'description'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'labTestId' => 'Lab Test ID',
            'testName' => 'Test Name',
            'status' => 'Status',
            'createdDate' => 'Created Date',
            'description' => 'Description',
            'updatedDate' => 'Updated Date',
            'updatedBy' => 'Updated By',
            'createdBy' => 'Created By',
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
}
