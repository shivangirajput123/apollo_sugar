<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Coach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coach-form">
<div class="box box-primary">
<div class="box-body">
  
    <?php $form = ActiveForm::begin(); ?>

   <?php if ($model->isNewRecord) 
        {?>
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    </div>
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
    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'readOnly'=>true]) ?>
    </div>
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'readOnly'=>true]) ?>
    </div>
		
		
		<?php }?>
 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'coachName')->textInput(['maxlength' => true]) ?>
</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'mobilenumber')->textInput(['maxlength' => 15]) ?>
</div>
	
	
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'coachDesription')->textarea(['rows' => 6]) ?>
</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'profileImage')->fileInput(['maxlength' => true]) ?>
	<?php
      if($model->profileImage){
     ?>
     <img src="<?php echo Url::base()."/".$model->profileImage ?>" height="100px" width="100px"> 
     <?php  
      }
    ?> 
</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'experience')->textInput(['maxlength' => true]) ?>
</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'qualification')->textInput(['maxlength' => true]) ?>
</div>

	  <div class="form-group col-lg-6 col-sm-12"> 

    <?= $form->field($model, 'cityId')->dropDownList($model->cities,['prompt'=>'Select City','id'=>'city-id'])  ?>
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
	 <div class="form-group col-lg-6 col-sm-12">
   

    <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true]) ?>
</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'metaDescription')->textarea(['rows' => 6]) ?>
</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true]) ?>
</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'seo_url')->textInput(['maxlength' => true]) ?>
</div>
	 <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
