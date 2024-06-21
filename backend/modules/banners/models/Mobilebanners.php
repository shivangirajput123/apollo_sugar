<?php

namespace backend\modules\banners\models;

use Yii;

/**
 * This is the model class for table "mobilebanners".
 *
 * @property int $id
 * @property string|null $baner_name
 * @property string|null $rate
 * @property string|null $item_name
 * @property string|null $itemcode
 * @property string|null $baner_image
 * @property int $status
 * @property int $priority
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $type
 */
class Mobilebanners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobilebanners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'baner_name', 'itemcode'], 'required'],
            [['status', 'priority'], 'integer'],
			['priority','unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['baner_name', 'rate', 'item_name', 'itemcode', 'baner_image', 'type'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'baner_name' => 'Baner Name',
            'rate' => 'Rate',
            'item_name' => 'Item Name',
            'itemcode' => 'Itemcode',
            'baner_image' => 'Baner Image',
            'status' => 'Status',
            'priority' => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type' => 'Type',
        ];
    }
}
