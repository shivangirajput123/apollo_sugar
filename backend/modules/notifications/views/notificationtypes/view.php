<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\notifications\models\Notificationtypes */

$this->title = $model->notificationTypeId;
$this->params['breadcrumbs'][] = ['label' => 'Notificationtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="notificationtypes-view">
<div class="box box-primary">
<div class="box-body">
   
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->notificationTypeId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->notificationTypeId], [
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
            'notificationTypeId',
            'type',
            'description:ntext',
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
