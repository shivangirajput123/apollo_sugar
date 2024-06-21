<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "customernotifications".
 *
 * @property int $id
 * @property string|null $access_token
 * @property string|null $message
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Customernotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customernotifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['createdDate', 'updatedDate','time','type'], 'safe'],
            [['access_token'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => 'Access Token',
            'message' => 'Message',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
