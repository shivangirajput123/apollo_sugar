<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "diettrack".
 *
 * @property int $id
 * @property int|null $patId
 * @property int $mealType
 * @property string $mealName
 * @property string $time
 * @property string|null $createdDate
 * @property string|null $updatedDate
 */
class Diettrack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diettrack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patId', 'mealType'], 'integer'],
            [['mealType', 'mealName', 'time'], 'required'],
            [['createdDate', 'updatedDate','itemId','itemName','quantity','cal'], 'safe'],
            [['mealName', 'time'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patId' => 'Pat ID',
            'mealType' => 'Meal Type',
            'mealName' => 'Meal Name',
            'time' => 'Time',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
