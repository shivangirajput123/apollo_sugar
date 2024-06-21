<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\modules\callcentre\models\Callcentre */

$this->title = 'Booking Status';
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['patients']];

?>
<div class="callcentre-form">
<div class="box box-primary">
<div class="box-body">
  
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group col-lg-6 col-sm-12">
		<?= $form->field($model, 'username')->textInput(['readOnly'=>true]) ?>
     </div>
	 <div class="form-group col-lg-6 col-sm-12">
		<?= $form->field($model, 'planname')->textInput(['readOnly'=>true]) ?>
     </div>
	 <div class="form-group col-lg-6 col-sm-12">
		<?= $form->field($model, 'price')->textInput(['readOnly'=>true]) ?>
     </div>
	 <div class="form-group col-lg-6 col-sm-12">
		<?= $form->field($model, 'events')->widget(Select2::classname(), [
    'data' => $model->upcomingevents,
    'language' => 'de',
    'options' => ['placeholder' => 'Select event ...'],
    'pluginOptions' => [
        'allowClear' => true,
		'multiple'=>true
    ],
]); ?>
     </div>
     <div class="form-group col-lg-6 col-sm-12">
		<?= $form->field($model, 'status')->dropDownList([ 'Completed' => 'Completed', 'Pending' => 'Pending','Cancel' => 'Cancel' ,'Sample Received'=>'Sample Received','Reports Generated'=>'Reports Generated'],[ 'prompt' => 'Please Select' ]) ?>
     </div>
	 <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

