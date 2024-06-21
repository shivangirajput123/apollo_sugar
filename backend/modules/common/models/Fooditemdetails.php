<?php

namespace backend\modules\common\models;

use Yii;
use yii\validators\RequiredValidator;
/**
 * This is the model class for table "fooditemdetails".
 *
 * @property int $fooditemId
 * @property int|null $itemId
 * @property string $quantity
 * @property string|null $cal
 * @property string|null $carbohydrates
 * @property string|null $proteins
 * @property string|null $fat
 * @property string|null $fiber
 */
class Fooditemdetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fooditemdetails';
    }
	public $calories;
	public $portions;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['itemId'], 'integer'],
          //  [['quantity'], 'required'],
           // [['quantity'], 'string'],
           // [['cal', 'carbohydrates', 'proteins', 'fat', 'fiber'], 'string', 'max' => 200],
		   [['calories','portions','portionId'],'safe'],
		   
		  // ['quantity','validatequantity'],
		   ['calories','validatecalories'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fooditemId' => 'Fooditem ID',
            'itemId' => 'Item ID',
            'quantity' => 'Quantity',
            'cal' => 'Cal',
            'carbohydrates' => 'Carbohydrates',
            'proteins' => 'Proteins',
            'fat' => 'Fat',
            'fiber' => 'Fiber',
        ];
    }
	
	
	
	
	public function validatecalories($attribute)
    {
    
    	$requiredValidator = new RequiredValidator();
    
		$quantity = [];
    	foreach($this->$attribute as $index => $row) {
    		$error = null;
			
    		$requiredValidator->validate($row['cal'], $error);
    		if (!empty($error)) {
    			$key = $attribute . '[' . $index . '][cal]';
    			$this->addError($key, 'calories cannot be blank.');
    		}
			$requiredValidator->validate($row['carbohydrates'], $error);
    		if (!empty($error)) {
    			$key = $attribute . '[' . $index . '][carbohydrates]';
    			$this->addError($key, 'carbohydrates cannot be blank.');
    		}
			$requiredValidator->validate($row['proteins'], $error);
    		if (!empty($error)) {
    			$key = $attribute . '[' . $index . '][proteins]';
    			$this->addError($key, 'proteins cannot be blank.');
    		}
			$requiredValidator->validate($row['fat'], $error);
    		if (!empty($error)) {
    			$key = $attribute . '[' . $index . '][fat]';
    			$this->addError($key, 'fat cannot be blank.');
    		}
			$requiredValidator->validate($row['fiber'], $error);
    		if (!empty($error)) {
    			$key = $attribute . '[' . $index . '][fiber]';
    			$this->addError($key, 'fiber cannot be blank.');
    		}
    
    	}
    }
	
}
