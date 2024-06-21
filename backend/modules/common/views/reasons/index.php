<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\ReasonsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reasons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reasons-index">
<div class="box box-primary">
<div class="box-body">
   

    <p>
        <?= Html::a('Add Reason', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description:ntext',
            'Status',
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>