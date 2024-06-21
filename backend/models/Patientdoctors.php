<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "patientdoctors".
 *
 * @property int $id
 * @property int|null $patientId
 * @property int|null $doctorId
 * @property string $doctorName
 * @property int $dieticianId
 * @property string $dieticianName
 * @property int $coachId
 * @property string $coachName
 * @property string|null $createdDate
 * @property string|null $updatedDate
 */
class Patientdoctors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patientdoctors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patientId', 'doctorId', 'dieticianId', 'coachId'], 'integer'],
            [['doctorName', 'dieticianId', 'dieticianName', 'coachId', 'coachName'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['doctorName', 'dieticianName', 'coachName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patientId' => 'Patient ID',
            'doctorId' => 'Doctor ID',
            'doctorName' => 'Doctor Name',
            'dieticianId' => 'Dietician ID',
            'dieticianName' => 'Dietician Name',
            'coachId' => 'Coach ID',
            'coachName' => 'Coach Name',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
