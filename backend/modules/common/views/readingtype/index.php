<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\ReadingtypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Readingtypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="readingtype-index">
<div class="box box-primary">
<div class="box-body">

    <p>
        <?= Html::a('Add Readingtype', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'type',
            'description:ntext',
            'status',
            //'createdDate',
            //'updatedDate',
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>
</div>
