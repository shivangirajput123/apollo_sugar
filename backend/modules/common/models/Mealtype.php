<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "mealtype".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $description
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $status
 */
class Mealtype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mealtype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdBy', 'updatedBy'], 'integer'],
            [['createdDate', 'updatedDate','ipAddress'], 'safe'],
            [['status'], 'string'],
            [['type'],'required'],
            [['type', 'description'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'description' => 'Description',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'status' => 'Status',
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
	
	public static function getTypes()
    {
        $model = Mealtype::find()->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['id']] = $value['type'];
        }
        return $data;
    }
}
