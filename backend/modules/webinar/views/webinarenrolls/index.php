<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Userprofile;
use common\models\User;
use backend\modules\webinar\models\Webinars;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\webinar\models\WebinarenrollsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Webinarenrolls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webinarenrolls-index">
<div class="box box-primary">
<div class="box-body">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'enrolId',
            [
				'attribute'=>'Webinar Name',
				'value'=>function($data)
				{
					$user =Webinars::find()->where(['webnarId'=>$data->webinarId])->one();
					return $user->webinarName;
				}
			],
			[
				'attribute'=>'Time',
				'value'=>function($data)
				{
					$user =Webinars::find()->where(['webnarId'=>$data->webinarId])->one();
					return $user->time;
				}
			],
			[
				'attribute'=>'Doctor Name',
				'value'=>function($data)
				{
					$user =Webinars::find()->where(['webnarId'=>$data->webinarId])->one();
					return $user->time;
				}
			],
            'access_token',
           // 'createdDate',
           // 'ipAddress',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>