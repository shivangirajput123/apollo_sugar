<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "appointmentrecords".
 *
 * @property int $apRecordId
 * @property int|null $apId
 * @property string|null $bp
 * @property string|null $sugar
 * @property string|null $weight
 * @property string|null $postprandial
 * @property string|null $HbA1c
 * @property string|null $creatinine
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $remarks
 */
class Appointmentrecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appointmentrecords';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apId'], 'integer'],
            [['createdDate', 'updatedDate','BMI'], 'safe'],
            [['bp', 'sugar', 'weight', 'postprandial', 'HbA1c', 'creatinine', 'remarks'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'apRecordId' => 'Ap Record ID',
            'apId' => 'Ap ID',
            'bp' => 'Bp',
            'sugar' => 'Sugar',
            'weight' => 'Weight',
            'postprandial' => 'Postprandial',
            'HbA1c' => 'Hb A1c',
            'creatinine' => 'Creatinine',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'remarks' => 'Remarks',
        ];
    }
}
