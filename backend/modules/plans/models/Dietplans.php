<?php

namespace backend\modules\plans\models;

use Yii;
use yii\validators\RequiredValidator;
/**
 * This is the model class for table "dietplans".
 *
 * @property int $planId
 * @property int|null $userId
 * @property string|null $time
 * @property int|null $mealtypeId
 * @property string|null $mealtype
 * @property int|null $itemId
 * @property string|null $itemName
 * @property int|null $quantity
 * @property string|null $calories
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $ipAddress
 */
class Dietplans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dietplans';
    }
    public $times;
	public $mealtypes;
	public $fooditems;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'mealtypeId', 'itemId', 'quantity', 'createdBy', 'updatedBy'], 'integer'],
            //[['createdDate', 'updatedDate'], 'required'],
            [['createdDate', 'updatedDate','times','mealtypes','fooditems','foodtype'], 'safe'],
            [['time', 'mealtype', 'itemName', 'calories', 'ipAddress','quantity'], 'string', 'max' => 200],
			['times','validatetimes'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'planId' => 'Plan ID',
            'userId' => 'User ID',
            'time' => 'Time',
            'mealtypeId' => 'Mealtype ID',
            'mealtype' => 'Mealtype',
            'itemId' => 'Item ID',
            'itemName' => 'Item Name',
            'quantity' => 'Quantity',
            'calories' => 'Calories',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
	public function validatetimes($attribute)
    {
    
    	$requiredValidator = new RequiredValidator();
    
		$quantity = [];
    	foreach($this->$attribute as $index => $row) {
    		$error = null;
			//print_r($row);exit;
    		$requiredValidator->validate($row['time'], $error);
    		if (!empty($error)) {
    			$key = $attribute . '[' . $index . '][time]';
    			$this->addError($key, 'time cannot be blank.');
    		}
			foreach($row['items'] as $k=>$r)
			{
				$requiredValidator->validate($r['quantity'], $error);
				if (!empty($error)) {
					$key = $attribute  . '[' . $index . '[items]'. '[' . $k . '][quantity]';
					$this->addError($key, 'quantity cannot be blank.');
				}
				
				$requiredValidator->validate($r['calories'], $error);
				if (!empty($error)) {
					$key = $attribute  . '[' . $index . '[items]'. '[' . $k . '][calories]';
					$this->addError($key, 'calories cannot be blank.');
				}
			}
			
    	}
    }
}
