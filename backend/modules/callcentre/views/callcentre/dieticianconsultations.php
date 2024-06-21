<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinics\models\ClinicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dietician Consultations';
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
			'bookingId',
            'slotTime',
			[
				'attribute' => 'slotDate',
				'value' => 'slotDate',
				'filter' => DatePicker::widget([
					'model'=>$searchModel,
					'attribute'=>'slotDate',
					'name' => 'CallcentreSearch[order_date]',
                    'value' => ArrayHelper::getValue($_GET, "CallcentreSearch.order_date"),
					//'language' => 'ru',
					//'dateFormat' => 'dd-MM-yyyy',
					 'pluginOptions' => [
                     'format' => 'yyyy-mm-dd',
                     'autoclose' => true,
                 ]
				]),
				'format' => 'html',
			],
            'firstName',
            'dieticianName',
            'createdDate',
			[
				'attribute'=>'status',
				'label' => 'Status',
				'filter' => Html::activeDropDownList($searchModel, 'status', ['Completed' => 'Completed','Cancel' => 'Cancel','Reshedule'=>'Reshedule','Pending'=>'Pending'],['class'=>'form-control','prompt' => 'Status']),
			],
			//'clinicName',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipaddress',

            ['class' => 'yii\grid\ActionColumn',
			'template' => '{view} {tests} {dietician}',
			'buttons' => [
            		'view' => function ($url,$data) {
						
            		$url = Url::to(['/callcentre/callcentre/dieticianbookingstatus','id'=>$data['bookingId']]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-eye-open"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Doctor Consultations',]);
            		}
            	],
			],
        ],
    ]); ?>
</div>
</div>

</div>
