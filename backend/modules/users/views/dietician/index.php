<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\models\DieticianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dieticians';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dietician-index">
<div class="box box-primary">
<div class="box-body">
  
    <p>
        <?= Html::a('Add Dietician', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'dieticianName',
            'email:email',
            'cityName',
            'locationName',
            
            //'doctorDesription:ntext',
           // 'profileImage',
			 [
            'attribute'=>'profileImage',
            'format' => 'html',
            'value' =>  function ($data){
              
                $baseurl = Url::base();
                return Html::img($baseurl.'/'.$data['profileImage'],['width'=>'50px']);
            },
            ],
            'experience',
            'qualification',
            //'membership',
            'Status',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',
            //'metaTitle',
            //'metaDescription:ntext',
            //'metaKeywords',
            //'seo_url:url',

            ['class' => 'yii\grid\ActionColumn',
            //'header'=>'Subjects View',
            'template' => '{view} {update} {delete} {slots}',
            'buttons' => [
            		'slots' => function ($url,$data) {
            		$url = Url::to(['/users/dietician/slots','id'=>$data->dieticianId,'date'=>date('Y-m-d'),'enddate'=>date('Y-m-d')]);
            		return Html::a(
            				'<span class="glyphicon glyphicon-plus"></span>',
            				$url,[// to prevent breaking table on hover
            						'title' => 'Slots',]);
            		},
            
            
            		],
            		],
        ],
    ]); ?>


</div>
</div>

</div>
