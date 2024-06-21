<?php

namespace backend\modules\plans\models;

use Yii;

/**
 * This is the model class for table "dietplandetails".
 *
 * @property int $dietplanId
 * @property int|null $planId
 * @property int|null $itemId
 * @property string|null $itemName
 * @property string|null $quantity
 * @property string|null $calories
 */
class Dietplandetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dietplandetails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId', 'itemId'], 'integer'],
            [['itemName', 'quantity'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dietplanId' => 'Dietplan ID',
            'planId' => 'Plan ID',
            'itemId' => 'Item ID',
            'itemName' => 'Item Name',
            'quantity' => 'Quantity',
            'calories' => 'Calories',
        ];
    }
}
