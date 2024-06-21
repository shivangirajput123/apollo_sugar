<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\article\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-index">

    <div class="box box-primary">
<div class="box-body">
  

    <p>
        <?= Html::a('Add Article', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
      
            'title',
           [
				'attribute'=>'Description',
				'value' =>  function($data){
					return str_replace("&nbsp;", "",strip_tags($data->Description));
				},            
			],
			'categoryName',
            'Url:url',
            //'file',
           
            //'createdBy',
            //'updatedBy',
            'createdDate',
			 'Status',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>