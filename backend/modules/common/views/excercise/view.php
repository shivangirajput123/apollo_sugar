<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Excercise */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Excercises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="excercise-view">
<div class="box box-primary">
<div class="box-body">
    

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ExcerciseId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ExcerciseId], [
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
            'ExcerciseId',
            'categoryName',
            'categoryId',
            'title',
            'Description:ntext',
            'Url:url',
            'file',
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