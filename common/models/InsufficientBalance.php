<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "insufficient_balance".
 *
 * @property int $id
 * @property string|null $mobilenumber
 * @property string|null $nooftimes
 * @property string|null $createdDate
 */
class InsufficientBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insufficient_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdDate'], 'safe'],
            [['mobilenumber', 'nooftimes'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobilenumber' => 'Mobilenumber',
            'nooftimes' => 'Nooftimes',
            'createdDate' => 'Created Date',
        ];
    }
}
