<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "medicinecart".
 *
 * @property int $medicinecartId
 * @property string|null $access_token
 * @property string|null $medicineName
 * @property int|null $medicineId
 * @property string|null $createdDate
 * @property string|null $updatedDate
 */
class Medicinecart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medicinecart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['medicineId'], 'integer'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['access_token', 'medicineName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'medicinecartId' => 'Medicinecart ID',
            'access_token' => 'Access Token',
            'medicineName' => 'Medicine Name',
            'medicineId' => 'Medicine ID',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
