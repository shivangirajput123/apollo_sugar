<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\Webinars */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="webinars-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'webinarName')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'starttime')->dropDownList([
			    "12:00 AM"=>"12:00 AM",
				"01:00 AM"=>"01:00 AM",
				"02:00 AM"=>"02:00 AM",
				"03:00 AM"=>"03:00 AM",
				"04:00 AM"=>"04:00 AM",
				"05:00 AM"=>"05:00 AM",
				"06:00 AM"=>"06:00 AM",
				"07:00 AM"=>"07:00 AM",
				"08:00 AM"=>"08:00 AM",
				"09:00 AM"=>"09:00 AM",
				"10:00 AM"=>"10:00 AM",
				"11:00 AM"=>"11:00 AM",
				"12:00 PM"=>"12:00 PM",
				"01:00 PM"=>"01:00 PM",
				"02:00 PM"=>"02:00 PM",
				"03:00 PM"=>"03:00 PM",
				"04:00 PM"=>"04:00 PM",
				"05:00 PM"=>"05:00 PM",
				"06:00 PM"=>"06:00 PM",
				"07:00 PM"=>"07:00 PM",
				"08:00 PM"=>"08:00 PM",
				"09:00 PM"=>"09:00 PM",
				"10:00 PM"=>"10:00 PM",
				"11:00 PM"=>"11:00 PM",				
			 ],['prompt'=>'Select Time']) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'endtime')->dropDownList([
			    "12:00 AM"=>"12:00 AM",
				"01:00 AM"=>"01:00 AM",
				"02:00 AM"=>"02:00 AM",
				"03:00 AM"=>"03:00 AM",
				"04:00 AM"=>"04:00 AM",
				"05:00 AM"=>"05:00 AM",
				"06:00 AM"=>"06:00 AM",
				"07:00 AM"=>"07:00 AM",
				"08:00 AM"=>"08:00 AM",
				"09:00 AM"=>"09:00 AM",
				"10:00 AM"=>"10:00 AM",
				"11:00 AM"=>"11:00 AM",
				"12:00 PM"=>"12:00 PM",
				"01:00 PM"=>"01:00 PM",
				"02:00 PM"=>"02:00 PM",
				"03:00 PM"=>"03:00 PM",
				"04:00 PM"=>"04:00 PM",
				"05:00 PM"=>"05:00 PM",
				"06:00 PM"=>"06:00 PM",
				"07:00 PM"=>"07:00 PM",
				"08:00 PM"=>"08:00 PM",
				"09:00 PM"=>"09:00 PM",
				"10:00 PM"=>"10:00 PM",
				"11:00 PM"=>"11:00 PM",				
			 ],['prompt'=>'Select Time']) ?>
	</div>
 
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'doctorId')->dropDownList($model->doctors,['prompt'=>'Select doctor'])  ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'PublishDate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter birth date ...','value'=>date('d/m/y')],
    'pluginOptions' => [
        'autoclose' => true,
		'format'=>'dd/mm/yyyy',
		'startDate'=>date('d/m/y')
    ]
]);  ?>
	</div>	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'sent')->dropDownList([ 'forall' => 'All Users', 'Female' => 'only females', 'Male' => 'males' ,'enrolls'=>'only for enrolls']) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
	</div>
	<div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<?php $url = Yii::$app->urlManager->createUrl(['/notifications/notifications/create']) ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
/*$( document ).ready(function() {

	$('#webinars-isnotified').on('click',function(){
	     if($('input[name="Webinars[isNotified]"]:checked').val() == 1)
		 {
			 
			 var name = $('#webinars-webinarname').val();
			 window.location.href = "<?php echo $url;?>&name="+name;
		 }
		
	});
	
});*/

</script>