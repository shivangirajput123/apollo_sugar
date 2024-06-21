<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "patientallergies".
 *
 * @property int $patAgId
 * @property string|null $access_token
 * @property string|null $text
 * @property string $createdDate
 * @property string $updatedDate
 * @property int|null $createdBy
 * @property int|null $updatedBy
 */
class Patientallergies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patientallergies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['access_token'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'patAgId' => 'Pat Ag ID',
            'access_token' => 'Access Token',
            'text' => 'Text',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
        ];
    }
}
