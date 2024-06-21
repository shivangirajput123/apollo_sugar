<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "glucose".
 *
 * @property int $id
 * @property string|null $access_token
 * @property string|null $glucosevalue
 * @property string|null $pickdate
 * @property string|null $time
 * @property string|null $readingType
 * @property string|null $mealtype
 * @property string|null $mealtime
 * @property string|null $createdDate
 * @property string|null $updatedDate
 */
class Glucose extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'glucose';
    }
	public $patientid;
	public $patientname;
	public $agegender;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pickdate', 'createdDate', 'updatedDate','readingid','mealid','Status','patientid','patientname','agegender'], 'safe'],
            [['access_token',  'readingType', 'mealtype', 'mealtime'], 'string', 'max' => 200],
            [['time'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => 'Access Token',
            'glucosevalue' => 'Glucosevalue',
            'pickdate' => 'Pickdate',
			'patientid'=>'Patient ID',
			'patientname'=>'Patient Name',
			'agegender'=>'Age|Gender',
            'time' => 'Time',
            'readingType' => 'Reading Type',
            'mealtype' => 'Mealtype',
            'mealtime' => 'Mealtime',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
