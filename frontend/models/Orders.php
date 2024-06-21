<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $orderId
 * @property string|null $access_token
 * @property string|null $prebookingId
 * @property string|null $bookingStatus
 * @property string|null $slotDate
 * @property string|null $slotTime
 * @property string $createdDate
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slotDate', 'createdDate','visitId'], 'safe'],
            [['createdDate'], 'required'],
            [['access_token', 'prebookingId', 'bookingStatus', 'slotTime'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'orderId' => 'Order ID',
            'access_token' => 'Access Token',
            'prebookingId' => 'Prebooking ID',
            'bookingStatus' => 'Booking Status',
            'slotDate' => 'Slot Date',
            'slotTime' => 'Slot Time',
            'createdDate' => 'Created Date',
        ];
    }
}
