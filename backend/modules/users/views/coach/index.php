<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\models\CoachSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coaches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-index">
<div class="box box-primary">
<div class="box-body">
  
    <p>
        <?= Html::a('Add Coach', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'coachName',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>

</div>
