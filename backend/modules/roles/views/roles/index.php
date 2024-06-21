<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\roles\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="roles-index">

<div class="box box-primary">
<div class="box-body">

    <p>
        <?= Html::a('Add Role', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	
	

   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['style' => ['max-width' => '10px;']] ],

         //   'roleId',
            'roleName',
            'roleDes:ntext',
            'Status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>