<?php


use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\models\DoctorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doctors Roaster';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctors-index">
<div class="box box-primary">
<div class="box-body">
  
  <div class="form-group col-lg-3 col-sm-12">
  <label class="form-label">Date</label>
  <?= 
  DatePicker::widget([
    'options' => ['placeholder' => 'Enter Slot date ...','value' => date('Y-m-d')],
	'name' => 'date',
	'value' => date('Y-m-d'),
    'pluginOptions' => [
        'autoclose' => true,
		'format' => 'yyyy-mm-dd',
		'startDate' => date('Y-m-d'),
    ]
]);
  ?>
  </div>
  
  <?= \yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => 'Morning',
            //using a view file
            'content' => $this->render('morningslots'),
		    //'content' => 'This is some content for Member Tracker Tab',
        ],
        [
            'label' => 'AfterNoon',
            //using static content
            'content' => $this->render('morningslots')
        ],
        [
            'label' => 'Evening',
            'content' => $this->render('morningslots')
        ],
    ],
]); ?>
 
</div>
</div>
</div>
</div>
