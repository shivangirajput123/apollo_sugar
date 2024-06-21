<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "planitems".
 *
 * @property int $planItemId
 * @property int|null $planId
 * @property int|null $itemId
 * @property string|null $ItemName
 */
class Planitems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planitems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId', 'itemId'], 'integer'],
            [['ItemName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'planItemId' => 'Plan Item ID',
            'planId' => 'Plan ID',
            'itemId' => 'Item ID',
            'ItemName' => 'Item Name',
        ];
    }
}
