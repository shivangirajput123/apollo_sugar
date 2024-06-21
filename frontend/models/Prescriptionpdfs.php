<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "prescriptionpdfs".
 *
 * @property int $ppId
 * @property int|null $prescriptionId
 * @property string|null $access_token
 * @property string|null $fileName
 * @property string $createdDate
 * @property int|null $createdBy
 */
class Prescriptionpdfs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescriptionpdfs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescriptionId', 'createdBy'], 'integer'],
            [['createdDate'], 'required'],
            [['createdDate'], 'safe'],
            [['access_token', 'fileName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ppId' => 'Pp ID',
            'prescriptionId' => 'Prescription ID',
            'access_token' => 'Access Token',
            'fileName' => 'File Name',
            'createdDate' => 'Created Date',
            'createdBy' => 'Created By',
        ];
    }
}
