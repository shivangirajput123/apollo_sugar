<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Labtests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="labtests-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'testName')->textInput(['maxlength' => true]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>
	</div>
	<div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
