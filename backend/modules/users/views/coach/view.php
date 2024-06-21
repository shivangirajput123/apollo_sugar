<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Coach */

$this->title = $model->coachName;
$this->params['breadcrumbs'][] = ['label' => 'Coaches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$baseurl = Url::base();
?>
<div class="coach-view">
<div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coachId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coachId], [
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
            'coachId',
            'cityName',
            'locationName',
            'coachName',
			'mobilenumber',
            'email:email',
           
           
			  [
            'attribute'=>'profileImage',
            'value' =>  Html::a(Html::img($baseurl.'/'.$model->profileImage, ['alt'=>'some', 'class'=>'thing', 'height'=>'100px', 'width'=>'100px']), ['site/zoom']),
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