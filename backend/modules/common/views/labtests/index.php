<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\LabtestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Labtests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="labtests-index">
 <div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Add Test', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'labTestId',
            'testName',
            'status',
          //  'createdDate',
            'description',
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