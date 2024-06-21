<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "patientsymptoms".
 *
 * @property int $symId
 * @property string|null $access_token
 * @property string|null $text
 * @property string $createdDate
 * @property string $updatedDate
 * @property int|null $createdBy
 * @property int|null $updatedBy
 */
class Patientsymptoms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patientsymptoms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['access_token', 'text'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'symId' => 'Sym ID',
            'access_token' => 'Access Token',
            'text' => 'Text',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
        ];
    }
}
