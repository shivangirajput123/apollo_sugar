<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Readingtype */

$this->title = $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Readingtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="readingtype-view">
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
            'description:ntext',
            'createdBy',
            'updatedBy',
            [
                'attribute' => 'createdDate',
                'format' =>  ['date', 'php:d-M-Y H:i:s'],
            ],
            [
                'attribute' => 'updatedDate',
                'format' =>  ['date', 'php:d-M-Y H:i:s'],
            ],
            'ipAddress',
            'status',
        ],
    ]) ?>

</div>
</div>
    </div>