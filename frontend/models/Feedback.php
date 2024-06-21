<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string|null $access_token
 * @property string $medicines
 * @property string $symptoms
 * @property string $createdDate
 * @property string $updatedDate
 * @property string $ipAddress
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['medicines', 'symptoms', 'createdDate', 'updatedDate', 'ipAddress'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['access_token', 'medicines', 'symptoms'], 'string', 'max' => 200],
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
            'medicines' => 'Medicines',
            'symptoms' => 'Symptoms',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
