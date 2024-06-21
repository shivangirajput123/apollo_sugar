<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\ExcerciseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Excercises';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="excercise-index">
<div class="box box-primary">
<div class="box-body">
  
    <p>
        <?= Html::a('Add Excercise', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ExcerciseId',
            'categoryName',
            'title',
            'Description:ntext',
            'Url:url',
            //'file',
            'Status',
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