<?php

namespace backend\modules\users\models;

use Yii;
use yii\validators\RequiredValidator;
/**
 * This is the model class for table "slots".
 *
 * @property int $slotId
 * @property int|null $doctorId
 * @property string|null $slotDate
 * @property string|null $slotTime
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Slots extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slots';
    }
	public $slots;
	public $timings;
	public $duration;
	public $endDate;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctorId', 'createdBy', 'updatedBy'], 'integer'],
			['slotDate','required'],
            [['slotDate', 'createdDate', 'updatedDate','slots','timings','duration','endDate'], 'safe'],
		//	['slots','validateSlots']
           
			//[['slotDate'], 'date', 'min' => time(), 'minString' => date('d-m-Y'), 'format' => 'php:d-m-Y']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'slotId' => 'Slot ID',
            'doctorId' => 'Doctor ID',
            'slotDate' => 'Slot Date',
			'timings'=>'Timings(Eg:11:00 AM to 04:00 PM)',
			'duration'=>'Duration(Eg:40 Min)',
            'slotTime' => 'Slot Time',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
			//'duration'=>'Duration(min)'
        ];
    }
	
	
}
