<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\banners\models\MobilebannersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;

//print_r($baseurl);exit;
?>
<div class="mobilebanners-index">
<div class="box box-primary">
<div class="box-body">
  

    <p>
        <?= Html::a('Add Banner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'baner_name',
             [
            'attribute'=>'baner_image',
            'format' => 'html',
            'value' =>  function ($data){
                $url = Url::base();
                $baseurl = str_replace('backend','frontend',$url);
                return Html::img($baseurl.'/'.$data['baner_image'],['width'=>'50px']);
            },
            ],
            'itemcode',
            'status',
            'priority',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>
</div>
