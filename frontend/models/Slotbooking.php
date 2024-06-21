<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "slotbooking".
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
 * @property int|null $doctorId
 * @property int|null $dieticianId
 */
class Slotbooking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slotbooking';
    }
	public $events;
	public $upcomingevents;
	public $username;
	public $planname;
	public $price;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slotId', 'doctorId', 'dieticianId'], 'integer'],
            [['slotDate', 'status', 'createdDate', 'updatedDate'], 'required'],
            [['slotDate', 'createdDate', 'updatedDate','dieticianId','videolink',
			'upcomingevents','events','username','planname','price','resheduleremarks','cancelremarks'], 'safe'],
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
            'doctorId' => 'Doctor ID',
            'dieticianId' => 'Dietician ID',
        ];
    }
}
