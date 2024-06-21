<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Mealtype */

$this->title = $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Mealtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mealtype-view">
<div class="box box-primary">
<div class="box-body">
  
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'type',
            'description',
            'createdBy',
            'updatedBy',
            'createdDate',
            'updatedDate',
            'status',
			'ipAddress'
        ],
    ]) ?>

</div>
</div>
</div>