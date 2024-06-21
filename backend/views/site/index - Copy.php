<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
use frontend\models\Userprofile;
use frontend\models\Glucose;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = "Today's Sugar Reports";
?>
<style>
body{
margin-top:20px;
background-color: #f7f7ff;
}
.radius-10 {
    border-radius: 10px !important;
}

.border-info {
    border-left: 5px solid  #0dcaf0 !important;
}
.border-danger {
    border-left: 5px solid  #fd3550 !important;
}
.border-success {
    border-left: 5px solid  #15ca20 !important;
}
.border-warning {
    border-left: 5px solid  #ffc107 !important;
}


.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0px solid rgba(0, 0, 0, 0);
    border-radius: .25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
}
.card-body
{
	height:100px;	
	margin-left:50px;
	margin-top:30px;
}
.bg-gradient-scooter {
    background: #17ead9;
    background: -webkit-linear-gradient( 
45deg
 , #17ead9, #6078ea)!important;
    background: linear-gradient( 
45deg
 , #17ead9, #6078ea)!important;
}
.widgets-icons-2 {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ededed;
    font-size: 27px;
    border-radius: 10px;
}
.rounded-circle {
    border-radius: 50%!important;
}
.text-white {
    color: #fff!important;
}
.ms-auto {
    margin-left: auto!important;
}
.bg-gradient-bloody {
    background: #f54ea2;
    background: -webkit-linear-gradient( 
45deg
 , #f54ea2, #ff7676)!important;
    background: linear-gradient( 
45deg
 , #f54ea2, #ff7676)!important;
}

.bg-gradient-ohhappiness {
    background: #00b09b;
    background: -webkit-linear-gradient( 
45deg
 , #00b09b, #96c93d)!important;
    background: linear-gradient( 
45deg
 , #00b09b, #96c93d)!important;
}

.bg-gradient-blooker {
    background: #ffdf40;
    background: -webkit-linear-gradient( 
45deg
 , #ffdf40, #ff8359)!important;
    background: linear-gradient( 
45deg
 , #ffdf40, #ff8359)!important;
}

.col-lg-3 {
    width: 23% !important;
}
</style>
<div class="container">
	
</div>
<div class="roles-index">

<div class="box box-primary">
<div class="box-body">

   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
			[
				'attribute'=>'patientid',
				'value'=>function($data)
				{
					$user =Userprofile::find()->where(['access_token'=>$data->access_token])->one();
					return $user->profileId;
				}
			],
			[
				'attribute'=>'patientname',
				'value'=>function($data)
				{
					$user =Userprofile::find()->where(['access_token'=>$data->access_token])->one();
					return $user->firstName.' '.$user->lastName;
				}
			],
			[
				'attribute'=>'agegender',
				'value'=>function($data)
				{
					$user =Userprofile::find()->where(['access_token'=>$data->access_token])->one();
					return $user->age.' | '.$user->gender;
				}
			],
			[
				'attribute'=>'glucosevalue',
				'value'=>function($data)
				{
					$user =Glucose::find()->where(['access_token'=>$data->access_token])->average('glucosevalue');
					return ceil($user);
				}
			],
           // 'glucosevalue',
          /*  [
				'attribute'=>'Status',
				'value'=>function($data)
				{
					$user =Glucose::find()->where(['access_token'=>$data->access_token])->one();
					$glucose =Glucose::find()->where(['access_token'=>$data->access_token])->average('glucosevalue');
					if($glucose >=80 && $glucose <=130){
					$status = 'Normal'; 
					}
					
					if($glucose >=54 && $glucose <=80){
						$status = 'Moderate'; 
					}
					
					if($glucose >=130 && $glucose <=181){
						$status = 'Moderate'; 
					}
					
					if($glucose < 54 || $glucose >181){
						$status = 'Danger'; 
					}	
					//print_r($user);exit;
					return $status;
				}
			],*/

            ['class' => 'yii\grid\ActionColumn',
            //'header'=>'Subjects View',
            'template' => '{view}',
            'buttons' => [
            		'view' => function ($url,$data) {
            		$url = Url::to(['/site/graphview','access_token'=>$data->access_token,'type'=>'week']);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'View',]);
            		},
            
            
            		],
            		],
        ],
    ]); ?>


</div>
</div>
</div>