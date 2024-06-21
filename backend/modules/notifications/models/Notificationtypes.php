<?php

namespace backend\modules\notifications\models;

use Yii;

/**
 * This is the model class for table "notificationtypes".
 *
 * @property int $notificationTypeId
 * @property string|null $type
 * @property string $description
 * @property string $createdDate
 * @property string $updatedDate
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $ipAddress
 */
class Notificationtypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notificationtypes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['description'], 'string'],
            [['createdDate', 'updatedDate','Status'], 'safe'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['type', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'notificationTypeId' => 'Notification Type ID',
            'type' => 'Type',
            'description' => 'Description',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
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
	public static function getNotificationtypes()
    {
        $model = Notificationtypes::find()->where(['Status'=>'Active'])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['notificationTypeId']] = $value['type'];
        }
        return $data;
    }
}
