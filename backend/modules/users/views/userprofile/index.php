<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserprofileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userprofile-index">
<div class="box box-primary">
<div class="box-body">
   

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'profileId',
            'firstName',
			[
				'attribute'=>'Mobile',
				'format'=>'raw',
				'value'=>function($data)
				{
					$user = User::find()->where(['access_token'=>$data->access_token])->one();
					if(!empty($user))
					{
						return $user->username;
					}
					else
					{
						return '';
					}
				}
			],
           // 'userId',
            'lastName',
            'gender',
            'profilePic',
           // 'DOB',
			[
				'attribute'=>'DOB',
				'format'=>'raw',
				'value'=>function($data)
				{
					
					return date('d/m/Y',strtotime($data->DOB));
				}
			],
            'weight',
            'height',
            'age',
            
            //'createdDate',
            //'updatedDate',
            //'access_token',

            ['class' => 'yii\grid\ActionColumn',
            //'header'=>'Subjects View',
           // 'template' => '{view}  {excerciseplans} {dietplans} {plan}',
            
			 'template' => '{view}',
			'buttons' => [
			
            		'excerciseplans' => function ($url,$data) {
				     if(Yii::$app->user->identity->roleId == 4 || Yii::$app->user->identity->roleId == ""){
            		$url = Url::to(['/plans/excerciseplans/create','id'=>$data->userId]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Excercise Plans',]);
					 } 
            		},
					 
					
					 'dietplans' => function ($url,$data) {
						   if(Yii::$app->user->identity->roleId == 5 || Yii::$app->user->identity->roleId == ""){
            		$url = Url::to(['/plans/dietplans/create','id'=>$data->userId]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-apple"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Diet Plans',]);
									 } 
            		},
					
					 'plan' => function ($url,$data) {
						  
            		$url = Url::to(['/packages/plans/plandetails','id'=>'4']);
            		return Html::a(
            				'<span class="glyphicon glyphicon-level-up"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Plan Details',]);
									  
            		},
					
            ],
           
           
            
            
            		
            		],
        ],
    ]); ?>


</div>
</div>
</div>