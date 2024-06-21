<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\callcentre\models\CallcentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Callcentres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callcentre-index">
<div class="box box-primary">
<div class="box-body">
   
    <p>
        <?= Html::a('Add Callcentre', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'Name',
            'cityName',
           // 'cityId',
            //'stateId',
            'stateName',
            'mobilenumber',
            'email:email',
            //'description:ntext',
            //'profileImage',
            //'Status',
            //'metaTitle',
            //'metaDescription:ntext',
            //'metaKeywords',
            //'seo_url:url',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipaddress',
            //'userId',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
