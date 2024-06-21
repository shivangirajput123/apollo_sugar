<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\modules\notifications\models\Notifications */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notifications-form">
<?php $form = ActiveForm::begin(); ?>
<div class="box box-primary">
<div class="box-body">
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	</div>
	
 
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?> 
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>
	</div>
	
	<div class="form-group col-lg-12 col-sm-12">
	<label>Configurations	
	</div>
	
	<div class="form-group col-lg-6 col-sm-12"> 
    <?= $form->field($model, 'specificprogram')->dropDownList($model->programs,[''=>'Select program'])  ?>
    </div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'gender')->dropDownList([ ''=>'Select Gender','Female' => 'Female', 'Male' => 'Male','Both'=>'Both' ]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12"> 
    <?= $form->field($model, 'age')->dropDownList([''=>'Select','<' => '<', '>' => '>'])  ?>
    </div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'agevalue')->textInput(['maxlength' => true]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12 webinar"> 
    <?= $form->field($model, 'webinarId')->dropDownList($model->webinars,[''=>'Select Webinar'])  ?>
    </div>
	
    <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
		<?= Html::submitButton('Save & Send Notification', ['class' => 'btn btn-success']) ?>
    </div>
	
	
</div>
</div>
<?php ActiveForm::end(); ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
 /*$( document ).ready(function() {
	//alert()
	if($('input[name="Notifications[iswebinar]"]:checked').val() == 1){
			 $('.webinar').show();
		 }
		 else
		 {
			 $('.webinar').hide();
		 }
    
	$('#notifications-iswebinar').on('click',function(){
	     if($('input[name="Notifications[iswebinar]"]:checked').val() == 1){
			 $('.webinar').show();
		 }
		 else
		 {
			 $('.webinar').hide();
		 }
	});
	
});*/
</script>
