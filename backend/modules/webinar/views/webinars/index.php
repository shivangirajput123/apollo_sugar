<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\webinar\models\WebinarsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Webinars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webinars-index">

<div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Add Webinar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'webnarId',
            'webinarName',
            'time',
           // 'doctorId',
            'doctorName',
           // 'specialityId',
          //  'specialityName',
            'PublishDate',
			'meetingUrl',
            //'Description:ntext',
            'Status',
           // 'PublishStatus',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn',
            //'header'=>'Subjects View',
           // 'template' => '{view} {update} {delete} {changestatus}{notified}',
		    'template' => '{view} {update} {delete} {notified}',
            'buttons' => [
            		'changestatus' => function ($url,$data) {
            		$url = Url::to(['/webinar/webinars/changepublishstatus','id'=>$data->webnarId]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Change Status','data-confirm'=>"Are you sure you want to change the publish status",'data-method'=>"post"]);
            		},
            
                   'notified' => function ($url,$data) {
            		$url = Url::to(['/notifications/notifications/create','name'=>$data->webinarName]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus-sign"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Notified',]);
            		},
            		],
            		],
        ],
    ]); ?>


</div>
</div>
</div>