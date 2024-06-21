<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model backend\modules\franchies\models\Franchies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="franchies-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	</div>
	<?php if ($model->isNewRecord) 
        {?>
    
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
	
	
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    </div>
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'confirmpassword')->passwordInput(['maxlength' => true]) ?>
    </div>
		<?php }else{?>
		 
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'readOnly'=>true]) ?>
    </div>
		
		
		<?php }?>
 
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">

    <?= $form->field($model, 'mobilenumber')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12"> 

    <?= $form->field($model, 'cityId')->dropDownList($model->cities,['prompt'=>'Select City','id'=>'city-id'])  ?>
    </div>
	<div class="form-group col-lg-6 col-sm-12">

    <?= $form->field($model, 'centerCode')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12"> 

    <?= $form->field($model, 'centerName')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12"> 

    <?= $form->field($model, 'pccCode')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12"> 

    <?= $form->field($model, 'locationId')->widget(DepDrop::classname(), [
     'options' => ['id'=>'location-id'],
	 'data'=>$model->locations,
     'pluginOptions'=>[
	   //  'initialize' => true,
         'depends'=>['city-id'],
         'placeholder' => 'Select...',
         'url' => Url::to(['/common/location/sublocation','selected' => $model->locationId])
     ]
 ]); ?>
 </div>
 <div class="form-group col-lg-6 col-sm-12">
   

    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>
</div>
	<div class="form-group col-lg-12 col-sm-12">   
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>