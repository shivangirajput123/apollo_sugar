<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Medicinemaster */

$this->title = $model->medicineName;
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="medicinemaster-view">

    <div class="box box-primary">
<div class="box-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->medicineId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->medicineId], [
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
            'medicineId',
            'medicineName',
            'drugName',
			'type',
			'status',
            'createdDate',
            'updatedDate',
            'createdBy',
            'updatedBy',
            'ipAddress',
        ],
    ]) ?>

</div>

</div>
</div>