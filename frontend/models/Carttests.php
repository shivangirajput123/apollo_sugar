<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "carttests".
 *
 * @property int $cartId
 * @property int|null $itemId
 * @property string|null $access_token
 * @property string|null $itemName
 * @property string|null $price
 * @property string $createdDate
 * @property string $updatedDate
 */
class Carttests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carttests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId'], 'integer'],
           // [['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['access_token', 'itemName', 'price'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cartId' => 'Cart ID',
            'itemId' => 'Item ID',
            'access_token' => 'Access Token',
            'itemName' => 'Item Name',
            'price' => 'Price',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
}
