<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\FooditemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fooditems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fooditems-index">
<div class="box box-primary">
<div class="box-body">
  
    <p>
        <?= Html::a('Add Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'itemName',
            'itemDescription:ntext',
            'Status',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn',
			'template' => '{view} {update} {delete} {options}',
            'buttons' => [
            		'options' => function ($url,$data) {
            		$url = Url::to(['/common/fooditems/addcal','id'=>$data->itemId]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Calories',]);
            		},
            
            
            		],],
        ],
    ]); ?>


</div>
</div>
</div>