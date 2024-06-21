<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "excercisetrack".
 *
 * @property int $trackId
 * @property int|null $patId
 * @property int $excerciseId
 * @property string $excerciseName
 * @property string $time
 * @property string $createdDate
 * @property string $updatedDate
 */
class Excercisetrack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'excercisetrack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patId', 'excerciseId'], 'integer'],
            [['excerciseId', 'excerciseName', 'time', 'createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['excerciseName', 'time'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trackId' => 'Track ID',
            'patId' => 'Pat ID',
            'excerciseId' => 'Excercise ID',
            'excerciseName' => 'Excercise Name',
            'time' => 'Time',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
