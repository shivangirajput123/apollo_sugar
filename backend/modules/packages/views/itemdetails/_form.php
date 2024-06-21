<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\ItemDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-details-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'itemName')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'itemCode')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'aliasName')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'rate')->textInput(['type' => 'number','maxlength' => true]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'discount')->textInput(['type' => 'number']) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'itemDescription')->textarea(['rows' => 6]) ?>
</div>
   <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'test_type')->dropDownList([ 'pathtests' => 'Pathlogytests','consultation'=>'consultation','others'=>'others']) ?>
   </div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active']) ?>

   </div>
	<div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$( document ).ready(function() {
	$('#itemdetails-discount').keyup(function()
	{
		var price = $('#itemdetails-rate').val();
		var discount = $('#itemdetails-discount').val();
		var offervalue  = (price*discount)/100;
		$('#itemdetails-offerprice').val(price-offervalue);
	});
	$('#itemdetails-rate').keyup(function()
	{
		var price = $('#itemdetails-rate').val();
		var discount = $('#itemdetails-discount').val();
		var offervalue  = (price*discount)/100;
		$('#itemdetails-offerprice').val(price-offervalue);
	});
	
});
</script>