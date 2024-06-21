<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\LeavetypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Leavetypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leavetype-index">
<div class="box box-primary">
<div class="box-body">
   

    <p>
        <?= Html::a('Add Leavetype', ['create'], ['class' => 'btn btn-success']) ?>
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