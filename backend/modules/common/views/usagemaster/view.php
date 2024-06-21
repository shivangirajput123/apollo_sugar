<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Usagemaster */

$this->title = $model->usageName;
$this->params['breadcrumbs'][] = ['label' => 'Usages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usagemaster-view">
<div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->usageId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->usageId], [
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
            'usageId',
            'usageName',
			'status',
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