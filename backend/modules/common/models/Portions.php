<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "portions".
 *
 * @property int $portionId
 * @property string|null $portionName
 * @property string $Status
 * @property string $createdDate
 * @property string $updatedDate
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $ipAddress
 */
class Portions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['portionName'], 'required'],
            [['Status'], 'string'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['portionName'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'portionId' => 'Portion ID',
            'portionName' => 'Portion Name',
            'Status' => 'Status',
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
	
	public static function getPortions()
    {
        $model = Portions::find()->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['portionId']] = $value['portionName'];
        }
        return $data;
    }
}
