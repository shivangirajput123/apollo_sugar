<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\callcentre\models\Callcentre */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Callcentres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="callcentre-view">
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
            'Name',
            'cityName',
            'cityId',
            'stateId',
            'stateName',
            'mobilenumber',
            'email:email',
            'description:ntext',
            'profileImage',
            'Status',
            'metaTitle',
            'metaDescription:ntext',
            'metaKeywords',
            'seo_url:url',
            'createdBy',
            'updatedBy',
            'createdDate',
            'updatedDate',
            'ipaddress',
            'userId',
        ],
    ]) ?>

</div>
</div>
</div>