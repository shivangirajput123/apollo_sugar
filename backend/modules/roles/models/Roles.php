<?php

namespace backend\modules\roles\models;

use Yii;
use common\models\User;
/**
 * This is the model class for table "roles".
 *
 * @property int $roleId
 * @property string|null $roleName
 * @property string|null $roleDes
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 * @property string|null $Status
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['roleName'],'required'],
            [['roleName'],'unique'],
            [['roleDes', 'Status'], 'string'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['roleName', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'roleId' => 'Role ID',
            'roleName' => 'Role Name',
            'roleDes' => 'Role Des',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
            'Status' => 'Status',
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
			//$this->save();
        }
       // print_r($this->cityName);exit;
        return parent::beforeSave($insert);
    }
	
	
}
