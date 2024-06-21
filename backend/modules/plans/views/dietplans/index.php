<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\plans\models\DietplansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dietplans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dietplans-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dietplans', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'planId',
            'userId',
            'time',
            'mealtypeId',
            'mealtype',
            //'itemId',
            //'itemName',
            //'quantity',
            //'calories',
            //'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
