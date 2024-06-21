<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Specialties */

$this->title = $model->speciality_name;
$this->params['breadcrumbs'][] = ['label' => 'Specialties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="specialties-view">
<div class="box box-primary">
<div class="box-body">
   
    

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->speciality_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->speciality_id], [
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
            'speciality_id',
            'speciality_name',
            'speciality_title',
            'seo_url:url',
            'metaTitle',
            'metaDescription:ntext',
            'metaKeyword',
            'description:ntext',
            'cityId',
            'cityName',
            'locationId',
            'locatonName',
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