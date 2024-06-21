<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "packageitems".
 *
 * @property int $packageItemId
 * @property int|null $packageId
 * @property string|null $PackageName
 * @property int|null $itemId
 * @property string|null $ItemCode
 * @property string|null $itemName
 * @property float|null $price
 */
class Packageitems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packageitems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['packageId', 'itemId'], 'integer'],
            [['price'], 'number'],
            [['PackageName', 'ItemCode', 'itemName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'packageItemId' => 'Package Item ID',
            'packageId' => 'Package ID',
            'PackageName' => 'Package Name',
            'itemId' => 'Item ID',
            'ItemCode' => 'Item Code',
            'itemName' => 'Item Name',
            'price' => 'Price',
        ];
    }
	public static function getInclusionsById($id)
    {
        $model = Packageitems::find()->where(['packageId'=>$id])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$key] = $value['itemId'];
        }
        return $data;
    }
	
	
}
