<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\notifications\models\NotificationtypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificationtypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notificationtypes-index">
<div class="box box-primary">
<div class="box-body">
    <p>
        <?= Html::a('Add Notificationtypes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'notificationTypeId',
            'type',
            'description:ntext',
            //'createdDate',
            //'updatedDate',
            //'createdBy',
            //'updatedBy',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>