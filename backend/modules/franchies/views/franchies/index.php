<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\franchies\models\FranchiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Franchies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchies-index">

   

    <p>
        <?= Html::a('Add Franchie', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'type',
            'mobilenumber',
            'cityName', 
            'centerCode', 
            'centerName', 
            'pccCode',         
            'locationName',
			'Status',
            

            ['class' => 'yii\grid\ActionColumn',
			'template' => '{view} {update} {delete} {referals}',
			'buttons' => [
            		
					'referals' => function ($url,$data) {
						
            		$url = Url::to(['/franchies/franchies/referals','id'=>$data['userId']]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-eye-open"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Referals',]);
            		}
            	],
			],
        ],
    ]); ?>


</div>
