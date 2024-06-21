<?php

namespace backend\modules\webinar\models;

use Yii;

/**
 * This is the model class for table "webinarenrolls".
 *
 * @property int $enrolId
 * @property int|null $webinarId
 * @property string|null $access_token
 * @property string|null $createdDate
 * @property string|null $ipAddress
 */
class Webinarenrolls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'webinarenrolls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['webinarId'], 'integer'],
            [['createdDate'], 'safe'],
            [['access_token', 'ipAddress'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'enrolId' => 'Enrol ID',
            'webinarId' => 'Webinar ID',
            'access_token' => 'Access Token',
            'createdDate' => 'Created Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
