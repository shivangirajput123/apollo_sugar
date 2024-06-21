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
<div class="box box-primary">
<div class="box-body">
   

    

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $referals,
        //'filterModel' => $searchModel,
        'columns' => [
            'name',
			'gender',
			'age',
			'mobilenumber',
			'UHID',
			'programName',
			'MaxAmount',
			'amount',
			'clinicName',
			'referedstatus',
			['class' => 'yii\grid\ActionColumn',
			'template' => '{view} {update} ',
			'buttons' => [
            		
					'update' => function ($url,$data) {
						
            		$url = Url::to(['/franchies/franchies/referals','id'=>$data['FranchiesId']]);
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
</div>

</div>
