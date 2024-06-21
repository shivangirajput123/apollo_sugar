<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use backend\modules\clinics\models\Clinics;
use backend\modules\packages\models\Plandetails;
use frontend\models\Slotbooking;
use frontend\models\Dieticianslotbooking;
use frontend\models\Orders;
use backend\modules\packages\models\ItemDetails;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinics\models\ClinicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Patients';
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
			[
				'attribute'=>'Upcoming Events',
				'format'=>'raw',
				'value'=>function($data)
				{
					//print_r($data);exit;
					$upcomingdata = [];
					$start = strtotime($data['updatedDate']);
					$end = strtotime(date('Y-m-d'));
					$days_between = ceil(abs($end - $start) / 86400);
					$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['planId'=>$data['planId']])->distinct()->asArray()->all();
					//print_r($upcomingdetailsarraydata);exit;
				
					$upcomingdetails = Plandetails::find()->where(['>=','day',$days_between])->andwhere(['planId'=>$data['planId']])->asArray()->one();
					
					if($upcomingdetails != [])
					{
						$events = Plandetails::find()->where(['planId'=>$data['planId'],'day'=>$upcomingdetails['day'],'endday'=>$upcomingdetails['endday']])->all();
						if($data['updatedDate'] == date('Y-m-d'))
						{
							$upcomingdetails['day'] = date('M d,Y',strtotime($data['updatedDate']));
									
						}
						else
						{
							$upcomingdetails['day'] = date('M d,Y',strtotime("+".$upcomingdetails['day']." day", strtotime($data['updatedDate'])));
						
						}
						$upcomingdetails['endday'] = date('M d,Y',strtotime("+".$upcomingdetails['endday']." day", strtotime($data['updatedDate'])));;
				
						if(!empty($events))
						{
							foreach($events as $key=>$value)
							{
								if($value->text == 1)
								{
									$doctorconsultation = Slotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
									//print_r(($upcomingdetails['day']));exit;
									if(empty($doctorconsultation) || $doctorconsultation->status != 'Completed')
									{
										$upcomingdata[] ='Doctor Consultation';
									}
								}
								elseif($value->text == 2)
								{
									$dieticianconsultation = Dieticianslotbooking::find()->where(['access_token'=>$data['access_token']])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('bookingId DESC')->one();
									//print_r(($upcomingdetails['day']));exit;
									if(empty($dieticianconsultation) || $dieticianconsultation->status != 'Completed')
									{
										$upcomingdata[] ='Dietician Consultation';
									}
								}
								else
								{
									$orderbookings = Orders::find()->leftjoin('orderitems','orderitems.orderId=orders.orderId')->where(['orders.access_token'=>$data['access_token'],'orderitems.itemId'=>$value->text])->andwhere(['between','slotDate',date('Y-m-d',strtotime($upcomingdetails['day'])),date('Y-m-d',strtotime($upcomingdetails['endday']))])->orderBy('orderId DESC')->one();
									//print_r(date('Y-m-d',strtotime($upcomingdetails['day'])));exit;
									if(empty($orderbookings) || ($orderbookings->bookingStatus != 'Reports Generated' ))
									{
										 $orderitem = ItemDetails::find()->where(['itemId'=>$value->text])->one();
										 $upcomingdata[] =$orderitem->itemName;										 
									}
								}
							}
						}
					}
					return implode(',',$upcomingdata);
				}
			],
				[
				'attribute'=>'Upcoming Event Last Date',
				'format'=>'raw',
				'value'=>function($data)
				{
					//print_r($data);exit;
					$upcomingdata = [];
					$start = strtotime($data['updatedDate']);
					$end = strtotime(date('Y-m-d'));
					$days_between = ceil(abs($end - $start) / 86400);
					$upcomingdetails = Plandetails::find()->where(['>=','day',$days_between])->andwhere(['planId'=>$data['planId']])->asArray()->one();
					$upcomingdetails['endday'] = date('Y-m-d',strtotime("+".$upcomingdetails['endday']." day", strtotime($data['updatedDate'])));;
				
					return $upcomingdetails['endday'];
				}
			],
           // 'createdDate',
			'planName',
			//'clinicName',
			[
				'attribute' => 'clinicName',
				'value' => 'clinicName',
				'filter' => Html::activeDropDownList($searchModel, 'clinicName', ArrayHelper::map(Clinics::find()->asArray()->all(), 'clinicName', 'clinicName'),['class'=>'form-control','prompt' => 'Select Clinics']),
			],
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipaddress',

            ['class' => 'yii\grid\ActionColumn',
			'template' => '{view} {tests} {dietician}',
			'buttons' => [
            		/*'view' => function ($url,$data) {
						
            		$url = Url::to(['/callcentre/callcentre/doctorconsultations','token'=>$data['access_token']]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-eye-open"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Doctor Consultations',]);
            		},
					'tests' => function ($url,$data) {
						
            		$url = Url::to(['/callcentre/callcentre/tests','token'=>$data['access_token']]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Upcoming LTP Tests',]);
            		},*/
					'view' => function ($url,$data) {
						
            		$url = Url::to(['/callcentre/callcentre/dieticianbookingstatus','token'=>$data['access_token']]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-eye-open"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Change Status',]);
            		}
            	],
			],
        ],
    ]); ?>

</div>
</div>

</div>
