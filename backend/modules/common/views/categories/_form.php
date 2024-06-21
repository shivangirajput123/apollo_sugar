<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

   
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'categoryName')->textInput(['maxlength' => true]) ?>
	</div>
	 <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'categoryDes')->textarea(['rows' => 6]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'type')->dropDownList([ 'Article' => 'Article', 'Excercise' => 'Excercise', ],['prompt'=>'Select Type']) ?>
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