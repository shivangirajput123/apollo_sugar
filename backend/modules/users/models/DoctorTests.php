<?php

namespace backend\modules\users\models;

use Yii;

/**
 * This is the model class for table "doctor_tests".
 *
 * @property int $testId
 * @property int|null $prescriptionId
 * @property string|null $testname
 */
class DoctorTests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor_tests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescriptionId'], 'integer'],
            [['testname'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'testId' => 'Test ID',
            'prescriptionId' => 'Prescription ID',
            'testname' => 'Testname',
        ];
    }
}
