<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\plans\models\ExcerciseplansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Excerciseplans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="excerciseplans-index">
<div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Add Excercise To Patient', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           
            'username',
            'time',           
           // 'title',
           // 'distance',
            //'createdDate',
            //'updatedDate',
            //'updatedBy',
            //'createdBy',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>