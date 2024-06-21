<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Portions */

$this->title = $model->portionName;
$this->params['breadcrumbs'][] = ['label' => 'Portions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="portions-view">

   <div class="box box-primary">
<div class="box-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->portionId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->portionId], [
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
            'portionId',
            'portionName',
            'Status',
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