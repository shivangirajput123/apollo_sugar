<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "videocalls".
 *
 * @property int $videoId
 * @property int|null $doctorId
 * @property string|null $access_token
 * @property string|null $link
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property string|null $ipAddress
 */
class Videocalls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videocalls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['videoId'], 'required'],
            [['videoId', 'doctorId'], 'integer'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['access_token', 'link'], 'string', 'max' => 200],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'videoId' => 'Video ID',
            'doctorId' => 'Doctor ID',
            'access_token' => 'Access Token',
            'link' => 'Link',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
