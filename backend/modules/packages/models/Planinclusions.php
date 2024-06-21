<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "planinclusions".
 *
 * @property int|null $planIncId
 * @property int|null $planId
 * @property int|null $packageId
 * @property string|null $packageName
 * @property float|null $Price
 * @property float|null $offerPrice
 */
class Planinclusions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planinclusions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planIncId', 'planId', 'packageId'], 'integer'],
            [['Price', 'offerPrice'], 'number'],
            [['packageName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'planIncId' => 'Plan Inc ID',
            'planId' => 'Plan ID',
            'packageId' => 'Package ID',
            'packageName' => 'Package Name',
            'Price' => 'Price',
            'offerPrice' => 'Offer Price',
        ];
    }
	
	public static function getInclusionsById($id)
    {
        $model = Planinclusions::find()->where(['planId'=>$id])->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$key] = $value['packageId'];
        }
        return $data;
    }
	public static function getInclusions($id)
    {
       // $model = Planinclusions::find()->where(['planId'=>$id])->asArray()->all();
	    $model = Planitems::find()->where(['planId'=>$id])->orderBy("planItemId DESC")->asArray()->all();
		//print_r($model);exit;
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['itemId']] = $value['ItemName'];
        }
        return $data;
    }
}
