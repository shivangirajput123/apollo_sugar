<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\MedicinemasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Medicines';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicinemaster-index">
<div class="box box-primary">
<div class="box-body">
  
    <p>
        <?= Html::a('Add Medicine', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'medicineId',
            'medicineName',
            'drugName',
			'type',
			 'status',
           // 'createdDate',
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