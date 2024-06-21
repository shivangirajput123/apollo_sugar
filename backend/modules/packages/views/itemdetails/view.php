<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\ItemDetails */

$this->title = $model->itemName;
$this->params['breadcrumbs'][] = ['label' => 'Inclusions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="item-details-view">
<div class="box box-primary">
<div class="box-body">
  

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->itemId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->itemId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'itemId',
            'itemName',
            'itemCode',
            'aliasName',
            'rate',
           // 'offerPrice',
            'discount',
           // 'cityId',
            //'cityName',
            //'locationId',
            //'locationName',
            'itemDescription:ntext',
            'Status',
            'createdBy',
            'updatedBy',
            'createdDate',
            'updatedDate',
            'ipAddress',
        ],
    ]) ?>

</div>
</div>
</div>