<?php

namespace backend\modules\users\models;

use Yii;

/**
 * This is the model class for table "doctorspecialites".
 *
 * @property int $docspecId
 * @property int|null $doctorId
 * @property string|null $doctorName
 * @property int|null $specialityId
 * @property string|null $specialityName
 */
class Doctorspecialites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctorspecialites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctorId', 'specialityId'], 'integer'],
            [['doctorName', 'specialityName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'docspecId' => 'Docspec ID',
            'doctorId' => 'Doctor ID',
            'doctorName' => 'Doctor Name',
            'specialityId' => 'Speciality ID',
            'specialityName' => 'Speciality Name',
        ];
    }
}
