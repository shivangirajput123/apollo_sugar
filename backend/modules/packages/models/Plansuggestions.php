<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "plansuggestions".
 *
 * @property int $sugId
 * @property int|null $planId
 * @property string $hba1ccondition
 * @property float $hba1cvalue
 * @property string $gender
 * @property string $physicalactivity
 */
class Plansuggestions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plansuggestions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId'], 'integer'],
            [['hba1ccondition', 'hba1cvalue', 'gender', 'physicalactivity',
			'age','agevalue','diabeticcondtion','period','managediabetes','typicalday','explowsugar','pregnancyStatus','preexistingcondtion'], 'safe'],
            [['hba1cvalue'], 'number'],
            [['gender'], 'string'],
            [['hba1ccondition', 'physicalactivity'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sugId' => 'Sug ID',
            'planId' => 'Plan ID',
            'hba1ccondition' => 'HBA1C',
            'hba1cvalue' => 'Value',
            'gender' => 'Gender',
            'physicalactivity' => 'Physicalactivity',
			'age' => 'Age',
            'agevalue' => 'Age Value'
        ];
    }
}
