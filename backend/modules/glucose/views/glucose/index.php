<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\GlucoseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Glucoses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glucose-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Glucose', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'access_token',
            'glucosevalue',
            'pickdate',
            'time',
            //'readingType',
            //'readingid',
            //'mealid',
            //'mealtype',
            //'mealtime',
            //'createdDate',
            //'updatedDate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
