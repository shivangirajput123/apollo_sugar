<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\packages\models\PlansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sugar Programs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-index">
 <div class="box box-primary">
<div class="box-body">
    

    <p>
        <?= Html::a('Add Program', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           
            'PlanName',
         
           // 'tenture',
			'duration',
            'Price',
            'offerPrice',
            'discount',
            'Status',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',
			
            ['class' => 'yii\grid\ActionColumn',
            //'header'=>'Subjects View',
            'template' => '{view} {update} {delete} {viewplan} {plansuggestion}',
            'buttons' => [
            		'viewplan' => function ($url,$data) {
            		$url = Url::to(['/packages/plans/plandetails','id'=>$data->planId]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Plan Details',]);
            		},
                   'plansuggestion' => function ($url,$data) {
            		$url = Url::to(['/packages/plans/plansuggestion','id'=>$data->planId]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus-sign"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Plan Suggestions',]);
            		},           
            
            		],
            		],
        ],
    ]); ?>



</div>
</div>
</div>