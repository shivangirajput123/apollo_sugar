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
use frontend\models\Userplans;
use common\models\User;
use backend\modules\packages\models\ItemDetails;
use backend\modules\callcentre\models\Callcentre;
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
					$callcentre = new Callcentre();
					//print_r($data);exit;
					$newmodel = Userplans::find()->where(['access_token' => $data['access_token'],'Status'=>'Subcribed'])->one();
					$doctorname = User::find()->where(['id'=>$newmodel->doctorId])->one()->username;
					$dieticanName = "";
					if($newmodel->dieticianId)
					{
						$dieticanName = User::find()->where(['id'=>$newmodel->dieticianId])->one()->username;
					}
					$upcomingdata = [];
					$start = strtotime($data['updatedDate']);
					$end = strtotime(date('Y-m-d'));
					$days_between = ceil(abs($end - $start) / 86400);
					$upcomingdetailsarraydata = Plandetails::find()->select('day, endday')->where(['planId'=>$data['planId']])->distinct()->asArray()->all();
					//print_r($upcomingdetailsarraydata);exit;
					if($upcomingdetailsarraydata != [])
					{
						foreach($upcomingdetailsarraydata as $key=>$value)
						{
							$diet = Plandetails::find()->where(['day'=>$value['day'],'endday'=>$value['endday'],'text'=>2])->andwhere(['planId'=>$data['planId']])->asArray()->count();
							$doctor = Plandetails::find()->where(['day'=>$value['day'],'endday'=>$value['endday'],'text'=>1])->andwhere(['planId'=>$data['planId']])->asArray()->count();
							$event = $callcentre->eventchecking($value,$newmodel,$data['access_token'],$doctorname,$dieticanName);							
							
							if($diet == 0)
							{
								$event['dietbooking'] = 1;
							}
							if($doctor == 0)
							{
								$event['doctorbooking'] = 1;
							}
							if($event['doctorbooking'] == 0 || $event['dietbooking'] == 0 || $event['textbooking'] == 0)
							{
								$upcomingdata = $callcentre->upcomingdetails($value,$newmodel,$data['access_token'],$event,$doctorname,$dieticanName);
								break;
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
