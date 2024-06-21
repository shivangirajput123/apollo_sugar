<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "steptracker".
 *
 * @property int $id
 * @property string|null $access_token
 * @property string|null $steptype
 * @property string|null $date
 * @property string|null $count
 * @property string|null $cal
 * @property string|null $distance
 * @property string|null $createdDate
 * @property string|null $updatedDate
 */
class Steptracker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'steptracker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'createdDate', 'updatedDate'], 'safe'],
            [['access_token', 'steptype'], 'string', 'max' => 200],
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
            'steptype' => 'Steptype',
            'date' => 'Date',
            'count' => 'Count',
            'cal' => 'Cal',
            'distance' => 'Distance',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
