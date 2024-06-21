<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Plans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plans-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'PlanName')->textInput(['maxlength' => true]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'StartDate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter birth date ...','value'=>date('Y-m-d')],
    'pluginOptions' => [
        'autoclose' => true,
		'format'=>'yyyy-mm-dd',
		'startDate'=>date('Y-m-d')
    ]
]); ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Price')->textInput(['maxlength' => true,'readonly'=>true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'discount')->textInput(['type'=>'number','maxlength'=>2]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'offerPrice')->textInput(['maxlength' => true,'readonly'=>true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'aliasName')->textarea(['maxlength' => true,'rows'=>6]) ?>
	</div>
	
  
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'inclusions')->widget(Select2::classname(), [
    'data' => $model->items,
    'language' => 'En',
    'options' => ['placeholder' => 'Select Items ...','multiple' => true,],
    'pluginOptions' => [
        'allowClear' => true		
    ],
]); ?>
    </div>
	
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'newitems')->widget(Select2::classname(), [
     'options' => ['id'=>'plans-items','multiple' => true,],
	 'data'=>$model->itemsnew,
	 'language' => 'En',
     'pluginOptions'=>[
	   'allowClear' => true		
     ]
 ]); ?>
    </div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'referralbonus')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
	<?= $form->field($model, "doctordriven")->radioList([1 => 'Doctor Driven', 0 => 'general']); ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12 doctor">
	   <?= $form->field($model, "doctorId")->dropDownList($model->doctors,['prompt'=>'Select doctor']); ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
	<?= $form->field($model, "unlimdoctorcons")->checkbox(); ?>			
	<?= $form->field($model, "unlimdiecticiancons")->checkbox(); ?>			
	</div>
	
	 <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
<?php $url = Yii::$app->urlManager->createUrl(['packages/plans/getprices']) ?>
<?php $newurl = Yii::$app->urlManager->createUrl(['packages/plans/getnewprices']) ?>
<?php $itemurl = Yii::$app->urlManager->createUrl(['packages/plans/getitems']) ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$( document ).ready(function() {
	//alert()
	if($('input[name="Plans[doctordriven]"]:checked').val() == 1){
			 $('.doctor').show();
		 }
		 else
		 {
			 $('.doctor').hide();
		 }
    $('#plans-inclusions').on('change',function(){
		var options = $(this).val();
		$.ajax({
                 type: "GET",
                 url: '<?php echo $url;?>',
                 data: {options:options}  ,
                 success:function(data) 
                 {	 
				    var obj = jQuery.parseJSON(data);
                  	$('#plans-price').val(obj.price); 
				    $('#plans-offerprice').val(obj.offerprice);
                    $('#plans-discount').val(obj.discount);
					var price = $('#plans-price').val();
					var discount = $('#plans-discount').val();
					var offervalue  = (price*discount)/100;
					$('#plans-offerprice').val(price-offervalue);
                  }
                });
	
		var options = $(this).val();
		$.ajax({
                 type: "GET",
                 url: '<?php echo $itemurl;?>',
                 data: {options:options}  ,
                 success:function(data) 
                 {	 
				  //  var obj = jQuery.parseJSON(data);
                  	$('#plans-items').html(data); 
				   // $('#plans-offerprice').val(obj.offerprice);
                   // $('#plans-discount').val(obj.discount);
                  }
                });
	});
	$('#plans-items').on('change',function(){
		var options = $(this).val();
		$.ajax({
                 type: "GET",
                 url: '<?php echo $newurl;?>',
                 data: {options:options}  ,
                 success:function(data) 
                 {	 
				    var obj = jQuery.parseJSON(data);
                  	$('#plans-price').val(obj.price); 
				    $('#plans-offerprice').val(obj.offerprice);
                    $('#plans-discount').val(obj.discount);
					var price = $('#plans-price').val();
					var discount = $('#plans-discount').val();
					var offervalue  = (price*discount)/100;
					$('#plans-offerprice').val(price-offervalue);
                  }
                });
	});
	$('#plans-discount').keyup(function()
	{
		var price = $('#plans-price').val();
		var discount = $('#plans-discount').val();
		var offervalue  = (price*discount)/100;
		$('#plans-offerprice').val(price-offervalue);
	});
	$('#plans-price').keyup(function()
	{
		var price = $('#plans-price').val();
		var discount = $('#plans-discount').val();
		var offervalue  = (price*discount)/100;
		$('#plans-offerprice').val(price-offervalue);
	});
	$('#plans-doctordriven').on('click',function(){
	     if($('input[name="Plans[doctordriven]"]:checked').val() == 1){
			 $('.doctor').show();
		 }
		 else
		 {
			 $('.doctor').hide();
		 }
	});
	
});
</script>
