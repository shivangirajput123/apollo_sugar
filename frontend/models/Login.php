<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "login".
 *
 * @property int $loginId
 * @property int|null $userId
 * @property string|null $gcm_id
 * @property string|null $device_info
 * @property string|null $app_info
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class Login extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['gcm_id', 'device_info', 'app_info'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'loginId' => 'Login ID',
            'userId' => 'User ID',
            'gcm_id' => 'Gcm ID',
            'device_info' => 'Device Info',
            'app_info' => 'App Info',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
