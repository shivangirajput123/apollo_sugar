<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "dieticianslotsession".
 *
 * @property int $doctorslotId
 * @property int|null $bookingId
 * @property string|null $slotDate
 * @property string|null $session
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property int|null $createdBy
 * @property string|null $status
 */
class Dieticianslotsession extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dieticianslotsession';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bookingId', 'createdBy'], 'integer'],
            [['slotDate', 'createdDate', 'updatedDate'], 'safe'],
            [['session', 'status'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctorslotId' => 'Doctorslot ID',
            'bookingId' => 'Booking ID',
            'slotDate' => 'Slot Date',
            'session' => 'Session',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'createdBy' => 'Created By',
            'status' => 'Status',
        ];
    }
}
