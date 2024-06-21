<?php

namespace backend\modules\plans\models;

use Yii;
use yii\validators\RequiredValidator;
/**
 * This is the model class for table "excerciseplans".
 *
 * @property int $explanId
 * @property int|null $userId
 * @property string|null $time
 * @property int|null $excerciseId
 * @property string|null $title
 * @property string|null $distance
 * @property string|null $createdDate
 * @property string|null $updatedDate
 * @property int|null $updatedBy
 * @property int|null $createdBy
 * @property string|null $ipAddress
 */
class Excerciseplans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'excerciseplans';
    }
	public $excercises;
	public $users;
	public $excerciselist;
	public $times;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'excerciseId', 'updatedBy', 'createdBy'], 'integer'],
			//[['userId'],'required'],
            [['createdDate', 'updatedDate','username','excercises','users','excerciselist','times'], 'safe'],
            [['distance', 'ipAddress'], 'string', 'max' => 200],
			['times','validatetimes'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'explanId' => 'Explan ID',
            'userId' => 'User',
            'time' => 'Time',
            'excerciseId' => 'Excercise ID',
            'title' => 'Title',
            'distance' => 'Distance',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'updatedBy' => 'Updated By',
            'createdBy' => 'Created By',
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
			foreach($row['excercises'] as $k=>$r)
			{
				$requiredValidator->validate($r['distance'], $error);
				if (!empty($error)) {
					$key = $attribute  . '[' . $index . '[excercises]'. '[' . $k . '][distance]';
					$this->addError($key, 'distance cannot be blank.');
				}
			}
			
    	}
    }
	
}
