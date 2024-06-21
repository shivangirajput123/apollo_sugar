<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Labtests */

$this->title = $model->labTestId;
$this->params['breadcrumbs'][] = ['label' => 'Labtests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="labtests-view">

   <div class="box box-primary">
<div class="box-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->labTestId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->labTestId], [
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
            'labTestId',
            'testName',
            'status',
            'createdDate',
            'description',
            'updatedDate',
            'updatedBy',
            'createdBy',
            'ipAddress',
        ],
    ]) ?>

</div>
</div>
</div>