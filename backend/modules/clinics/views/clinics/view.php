<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinics\models\Clinics */

$this->title = $model->clinicName;
$this->params['breadcrumbs'][] = ['label' => 'Clinics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="clinics-view">

    

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->clinicId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->clinicId], [
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
            'clinicId',
            'clinicName',
            'cityName',
            'cityId',
            'stateId',
            'stateName',
            'createdBy',
            'updatedBy',
            'createdDate',
            'updatedDate',
            'ipaddress',
        ],
    ]) ?>

</div>
