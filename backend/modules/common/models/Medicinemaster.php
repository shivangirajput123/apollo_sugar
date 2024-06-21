<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "medicinemaster".
 *
 * @property int $medicineId
 * @property string|null $medicineName
 * @property string|null $drugName
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $ipAddress
 */
class Medicinemaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medicinemaster';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdDate', 'updatedDate','status','type'], 'safe'],
            [['createdBy', 'updatedBy'], 'integer'],
			[['medicineName', 'drugName'], 'required'],
            [['medicineName', 'drugName'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'medicineId' => 'Medicine ID',
            'medicineName' => 'Medicine Name',
            'drugName' => 'Drug Name',
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
}
