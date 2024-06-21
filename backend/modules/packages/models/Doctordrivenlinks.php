<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "doctordrivenlinks".
 *
 * @property int $doctorDrivenLinkId
 * @property int|null $programId
 * @property int|null $doctorId
 * @property string|null $mobilenumber
 */
class Doctordrivenlinks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctordrivenlinks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programId', 'doctorId'], 'integer'],
			[['status','name'],'safe'],
            [['mobilenumber'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctorDrivenLinkId' => 'Doctor Driven Link ID',
            'programId' => 'Program ID',
            'doctorId' => 'Doctor ID',
            'mobilenumber' => 'Mobilenumber',
        ];
    }
}
