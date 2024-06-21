<?php

namespace backend\modules\plans\models;

use Yii;

/**
 * This is the model class for table "excerciseplandetails".
 *
 * @property int $explandetId
 * @property int|null $explanId
 * @property int|null $excerciseId
 * @property string|null $title
 * @property string|null $distance
 */
class Excerciseplandetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'excerciseplandetails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['explanId', 'excerciseId'], 'integer'],
            [['title', 'distance'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'explandetId' => 'Explandet ID',
            'explanId' => 'Explan ID',
            'excerciseId' => 'Excercise ID',
            'title' => 'Title',
            'distance' => 'Distance',
        ];
    }
}
