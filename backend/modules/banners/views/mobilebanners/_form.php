<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Mobilebanners;
$url = Url::base();
$baseurl = str_replace('backend','frontend',$url);
/* @var $this yii\web\View */
/* @var $model common\models\Baner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baner-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'baner_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="form-group col-lg-6 col-sm-12">
	<?= $form->field($model, 'itemcode')->textInput(['maxlength' => true]) ?>
    </div>
    <?php
      if($model->baner_image)
	  {
     ?>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'baner_image')->fileInput(['id'=>'1','accept' => 'image/*'])->label('Image') ?>
    
     <img src="<?php echo $baseurl."/".$model->baner_image ?>" height="100px" width="100px"> 
     <?php  
      }
	  else{
    ?> 
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'baner_image')->fileInput(['id'=>'1','accept' => 'image/*','required'=>true])->label('Image') ?>
	  <?php }?>
    </div>
    <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'priority')->dropDownList(range(0, 15),['prompt'=>'Select Banner Priority']) ?>
    </div>
    <div class="form-group col-lg-6 col-sm-12">
    <?php echo $form->field($model, 'status')->dropDownList(['1'=>'Active','0'=>'In-active']) ?>
    </div>
    <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
