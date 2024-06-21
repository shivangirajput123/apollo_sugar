<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\common\models\UsagemasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usagemaster-index">
<div class="box box-primary">
<div class="box-body">
    
    <p>
        <?= Html::a('Add Usage', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'usageId',
            'usageName',
			'status',
           // 'createdBy',
            //'updatedBy',
            //'createdDate',
            //'updatedDate',
            //'ipAddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>