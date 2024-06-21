<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Doctors */

$this->title = $model->doctorName;
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$baseurl = Url::base();

?>
<div class="doctors-view">
<div class="box box-primary">
<div class="box-body">
   

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->doctorId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->doctorId], [
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
            'doctorId',            
            'cityName',
            'locationName',
            'doctorName',
			'mobilenumber',
            'email:email',
            
             [
            'attribute'=>'profileImage',
            'value' =>  (Html::img($baseurl.'/'.$model->profileImage, ['alt'=>'some', 'class'=>'thing', 'height'=>'100px', 'width'=>'100px'])),
            'format' => ['raw'],
        ],
            'experience',
            'qualification',
            'membership',
            'Status',
            'createdBy',
            'updatedBy',
            'createdDate',
            'updatedDate',
            'ipAddress',
            'metaTitle',
            'metaDescription:ntext',
            'metaKeywords',
            'seo_url:url',
        ],
    ]) ?>

</div>
</div>
</div>