<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "durations".
 *
 * @property int $durationId
 * @property string|null $name
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Durations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'durations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdBy', 'updatedBy'], 'integer'],
			[['name', 'status'], 'required'],
            [['createdDate', 'updatedDate','status'], 'safe'],
            [['name', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'durationId' => 'Duration ID',
            'name' => 'Name',
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
}
