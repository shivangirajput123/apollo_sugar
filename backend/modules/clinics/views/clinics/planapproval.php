<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinics\models\ClinicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Approval Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clinics-index">
<div class="box box-primary">
<div class="box-body">
  
    

   

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'clinicId',
			'firstName',
            'username',
            'price',
            'createdDate',
			'planName',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipaddress',

            ['class' => 'yii\grid\ActionColumn',
			'template' => '{view}',
			'buttons' => [
            		'view' => function ($url,$data) {
						
            		$url = Url::to(['/clinics/clinics/approval','id'=>$data['userPlanId']]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-eye-open"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Slots',]);
            		}           
            	],
			],
        ],
    ]); ?>

</div>
</div>

</div>
