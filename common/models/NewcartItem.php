<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "newcart_item".
 *
 * @property int $id
 * @property int $cart_id
 * @property int $item_id
 * @property string|null $test_code
 * @property string|null $item_name
 * @property int $quantity
 * @property float $price
 * @property float|null $NetAmt
 * @property float $other_charges
 * @property float $total_amount
 * @property float $item_discount
 * @property int $status
 * @property string|null $ItemType
 * @property string|null $SubCategoryID
 * @property string $created_at
 * @property string $updated_at
 */
class NewcartItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newcart_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['id', 'cart_id', 'item_id', 'quantity'], 'required'],
            [['id', 'cart_id', 'item_id', 'quantity', 'status'], 'integer'],
            [['price', 'NetAmt', 'other_charges', 'total_amount', 'item_discount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['test_code', 'ItemType'], 'string', 'max' => 100],
            [['item_name'], 'string', 'max' => 255],
            [['SubCategoryID'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cart_id' => 'Cart ID',
            'item_id' => 'Item ID',
            'test_code' => 'Test Code',
            'item_name' => 'Item Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'NetAmt' => 'Net Amt',
            'other_charges' => 'Other Charges',
            'total_amount' => 'Total Amount',
            'item_discount' => 'Item Discount',
            'status' => 'Status',
            'ItemType' => 'Item Type',
            'SubCategoryID' => 'Sub Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
