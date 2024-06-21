<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\notifications\models\NotificationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-index">
<div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Add Notification', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'title',
           [
				'attribute'=>'description',
				'value' =>  function($data){
					return str_replace("&nbsp;", "",strip_tags($data->description));
				},            
			],
			
            'Status',
			//'cityName',
			//'locationName',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>