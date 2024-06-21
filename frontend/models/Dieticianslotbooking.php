<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "dieticianslotbooking".
 *
 * @property int $bookingId
 * @property int|null $slotId
 * @property string|null $slotTime
 * @property string|null $access_token
 * @property string $slotDate
 * @property string|null $name
 * @property string $status
 * @property string $createdDate
 * @property string $updatedDate
 * @property int|null $dieticianId
 */
class Dieticianslotbooking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dieticianslotbooking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slotId', 'dieticianId'], 'integer'],
            [['slotDate', 'status', 'createdDate', 'updatedDate'], 'required'],
            [['slotDate', 'createdDate', 'updatedDate','videolink','resheduleremarks','cancelremarks'], 'safe'],
            [['slotTime', 'access_token', 'name', 'status'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bookingId' => 'Booking ID',
            'slotId' => 'Slot ID',
            'slotTime' => 'Slot Time',
            'access_token' => 'Access Token',
            'slotDate' => 'Slot Date',
            'name' => 'Name',
            'status' => 'Status',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'dieticianId' => 'Dietician ID',
        ];
    }
}
