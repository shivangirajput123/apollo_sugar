<?php

namespace backend\modules\plans\models;

use Yii;

/**
 * This is the model class for table "remarks".
 *
 * @property int $remarkId
 * @property string|null $access_token
 * @property string|null $text
 * @property string $createdDate
 * @property string $updatedDate
 * @property int $createdBy
 * @property int $updatedBy
 */
class Remarks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remarks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdDate', 'updatedDate', 'createdBy', 'updatedBy'], 'required'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['createdBy', 'updatedBy'], 'integer'],
            [['access_token', 'text'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'remarkId' => 'Remark ID',
            'access_token' => 'Access Token',
            'text' => 'Text',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
        ];
    }
}
