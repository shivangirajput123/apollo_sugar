<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "plandetails".
 *
 * @property int $plandetailId
 * @property int|null $planId
 * @property string|null $day
 * @property string|null $date
 * @property string|null $text
 * @property string|null $status
 * @property int|null $createdBy
 * @property int|null $updatedBy
 */
class Plandetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plandetails';
    }
	public $details;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId', 'createdBy', 'updatedBy'], 'integer'],
            [['date','details','endday'], 'safe'],
            [['text'], 'string'],
           // [['day','endday'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plandetailId' => 'Plandetail ID',
            'planId' => 'Plan ID',
            'day' => 'Day',
            'date' => 'Date',
            'text' => 'Text',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
        ];
    }
}
