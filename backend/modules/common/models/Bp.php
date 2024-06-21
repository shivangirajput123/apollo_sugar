<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "bp".
 *
 * @property int $bpId
 * @property string|null $SystolicValue
 * @property string $DiastolicValue
 * @property string $pickdate
 * @property string|null $time
 * @property string $createdDate
 * @property string $updatedDate
 * @property string|null $access_token
 */
class Bp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['bpId', 'DiastolicValue', 'pickdate', 'createdDate', 'updatedDate'], 'required'],
          //  [['bpId'], 'integer'],
            [['pickdate', 'createdDate', 'updatedDate'], 'safe'],
            [['SystolicValue', 'DiastolicValue', 'time', 'access_token'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bpId' => 'Bp ID',
            'SystolicValue' => 'Systolic Value',
            'DiastolicValue' => 'Diastolic Value',
            'pickdate' => 'Pickdate',
            'time' => 'Time',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'access_token' => 'Access Token',
        ];
    }
}
