<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\SpecialtiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Specialties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specialties-index">
<div class="box box-primary">
<div class="box-body">
   
    

    <p>
        <?= Html::a('Add Speciality', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'speciality_id',
            'speciality_name',
            'speciality_title',
            //'seo_url:url',
           // 'metaTitle',
            //'metaDescription:ntext',
            //'metaKeyword',
            //'description:ntext',
            //'cityId',
            'cityName',
            //'locationId',
            'locationName',
            'Status',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>