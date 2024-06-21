<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "appointments".
 *
 * @property int $apId
 * @property int|null $patId
 * @property int|null $doctorId
 * @property string|null $remarks
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class Appointments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appointments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patId', 'doctorId'], 'integer'],
            [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['remarks', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'apId' => 'Ap ID',
            'patId' => 'Pat ID',
            'doctorId' => 'Doctor ID',
            'remarks' => 'Remarks',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
