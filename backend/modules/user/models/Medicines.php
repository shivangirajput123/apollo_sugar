<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "medicines".
 *
 * @property int $medicineId
 * @property int|null $prescriptionId
 * @property string|null $medicineName
 * @property int|null $medicineMId
 * @property int|null $usageId
 * @property string|null $usageName
 * @property int|null $durationId
 * @property string|null $durationName
 */
class Medicines extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medicines';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescriptionId', 'medicineMId', 'usageId', 'durationId'], 'integer'],
            [['medicineName', 'usageName', 'durationName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'medicineId' => 'Medicine ID',
            'prescriptionId' => 'Prescription ID',
            'medicineName' => 'Medicine Name',
            'medicineMId' => 'Medicine M ID',
            'usageId' => 'Usage ID',
            'usageName' => 'Usage Name',
            'durationId' => 'Duration ID',
            'durationName' => 'Duration Name',
        ];
    }
}
