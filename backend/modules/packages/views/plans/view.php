<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Plans */

$this->title = $model->PlanName;
$this->params['breadcrumbs'][] = ['label' => 'Sugar Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="plans-view">
<div class="box box-primary">
<div class="box-body">
    
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->planId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->planId], [
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
            'planId',
            'PlanName',
            'aliasName',
			'inclusions',
        //    'tenture',
			'duration',
			
            'Price',
            'offerPrice',
            'discount',
			'referralbonus',
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