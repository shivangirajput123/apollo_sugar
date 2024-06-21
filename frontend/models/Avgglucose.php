<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "avgglucose".
 *
 * @property int $avgGId
 * @property string|null $access_token
 * @property string|null $avgGlucoseValue
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $deviceUsed
 */
class Avgglucose extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avgglucose';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['access_token',  'deviceUsed'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'avgGId' => 'Avg G ID',
            'access_token' => 'Access Token',
            'avgGlucoseValue' => 'Avg Glucose Value',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'deviceUsed' => 'Device Used',
        ];
    }
}
