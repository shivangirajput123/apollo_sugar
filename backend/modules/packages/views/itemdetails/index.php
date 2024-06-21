<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\packages\models\ItemDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inclusions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-details-index">

      <div class="box box-primary">
<div class="box-body">
  

    <p>
        <?= Html::a('Add Inclusion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'itemId',
            'itemName',
          //  'itemCode',
            'aliasName',
            'rate',
            
            'discount',
            //'cityId',
           
            //'itemDescription:ntext',
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
