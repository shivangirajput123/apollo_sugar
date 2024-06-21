<?php

namespace backend\modules\plans\models;

use Yii;

/**
 * This is the model class for table "userexcercisetrack".
 *
 * @property int $userExTrackId
 * @property string|null $access_token
 * @property string|null $time
 * @property string|null $createdDate
 * @property int|null $excerciseId
 * @property string|null $title
 * @property string|null $distance
 */
class Userexcercisetrack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userexcercisetrack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdDate'], 'safe'],
            [['excerciseId'], 'integer'],
            [['access_token', 'time', 'title', 'distance'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userExTrackId' => 'User Ex Track ID',
            'access_token' => 'Access Token',
            'time' => 'Time',
            'createdDate' => 'Created Date',
            'excerciseId' => 'Excercise ID',
            'title' => 'Title',
            'distance' => 'Distance',
        ];
    }
}
