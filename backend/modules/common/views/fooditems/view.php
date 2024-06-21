<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Fooditems */

$this->title = $model->itemName;
$this->params['breadcrumbs'][] = ['label' => 'Fooditems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fooditems-view">
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
