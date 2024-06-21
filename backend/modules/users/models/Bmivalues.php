<?php

namespace backend\modules\users\models;

use Yii;

/**
 * This is the model class for table "bmivalues".
 *
 * @property int $bmiId
 * @property string|null $access_token
 * @property string|null $height
 * @property string|null $weight
 * @property float|null $BMI
 * @property string $createdDate
 * @property string $updatedDate
 */
class Bmivalues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bmivalues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BMI'], 'number'],
            [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate','hba1c','pasthba1c','pastbmi','pastweight'], 'safe'],
            [['access_token'], 'string', 'max' => 200],
            [['height', 'weight'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bmiId' => 'Bmi ID',
            'access_token' => 'Access Token',
            'height' => 'Height',
            'weight' => 'Weight',
            'BMI' => 'Bmi',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
