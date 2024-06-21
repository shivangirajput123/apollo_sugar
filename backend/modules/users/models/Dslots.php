<?php

namespace backend\modules\users\models;

use Yii;

/**
 * This is the model class for table "dslots".
 *
 * @property int $slotId
 * @property int|null $dieticanId
 * @property string|null $slotDate
 * @property string|null $slotTime
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Dslots extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dslots';
    }

    /**
     * {@inheritdoc}
     */
	public $slots;
	public $timings;
	public $duration;
	public $endDate;
    public function rules()
    {
        return [
          //  [['dieticianId', 'createdBy', 'updatedBy'], 'integer'],
            [['slotDate', 'createdDate', 'updatedDate','slots','timings','duration','endDate','dieticianId'], 'safe'],
            [['slotTime', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'slotId' => 'Slot ID',
            'dieticianId' => 'Dietican ID',
            'slotDate' => 'Slot Date',
            'slotTime' => 'Slot Time',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
			'timings'=>'Timings(Eg:11:00 AM to 04:00 PM)',
			'duration'=>'Duration(Eg:40 Min)',
        ];
    }
}
