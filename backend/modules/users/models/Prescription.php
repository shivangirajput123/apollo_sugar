<?php

namespace backend\modules\users\models;

use Yii;

/**
 * This is the model class for table "prescription".
 *
 * @property int $prescriptionId
 * @property string|null $access_token
 * @property string $diagnosticCenter
 * @property string $type
 * @property string $createdDate
 * @property string $updatedDate
 * @property int $updatedBy
 * @property int $createdBy
 * @property string $ipAddress
 */
class Prescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['diagnosticCenter', 'type', 'createdDate', 'updatedDate', 'updatedBy', 'createdBy', 'ipAddress'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['updatedBy', 'createdBy'], 'integer'],
            [['access_token', 'diagnosticCenter', 'type'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prescriptionId' => 'Prescription ID',
            'access_token' => 'Access Token',
            'diagnosticCenter' => 'Diagnostic Center',
            'type' => 'Type',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'updatedBy' => 'Updated By',
            'createdBy' => 'Created By',
            'ipAddress' => 'Ip Address',
        ];
    }
}
