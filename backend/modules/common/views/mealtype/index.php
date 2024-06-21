<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\MealtypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mealtypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mealtype-index">

<div class="box box-primary">
<div class="box-body">


    <p>
        <?= Html::a('Add Mealtype', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'type',
            'description',           
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>
</div>
