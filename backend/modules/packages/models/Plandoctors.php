<?php

namespace backend\modules\packages\models;

use Yii;

/**
 * This is the model class for table "plandoctors".
 *
 * @property int $plandoctorId
 * @property int|null $planId
 * @property int|null $roleId
 * @property int|null $userId
 * @property string|null $name
 */
class Plandoctors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plandoctors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId', 'roleId', 'userId'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plandoctorId' => 'Plandoctor ID',
            'planId' => 'Plan ID',
            'roleId' => 'Role ID',
            'userId' => 'User ID',
            'name' => 'Name',
        ];
    }
}
