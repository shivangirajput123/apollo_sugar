<?php

namespace backend\modules\users\models;

use Yii;

/**
 * This is the model class for table "usermeals".
 *
 * @property int $usermealId
 * @property string|null $access_token
 * @property string|null $mealtype
 * @property int|null $itemId
 * @property string|null $qunatity
 */
class Usermeals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usermeals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId'], 'integer'],
			[['createdDate','portionId','fiber','fat','proteins','carbohydrates','cal'],'safe'],
            [['access_token', 'mealtype', 'qunatity'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usermealId' => 'Usermeal ID',
            'access_token' => 'Access Token',
            'mealtype' => 'Mealtype',
            'itemId' => 'Item ID',
            'qunatity' => 'Qunatity',
        ];
    }
}
