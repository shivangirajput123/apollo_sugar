<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Packages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="packages-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'packageName')->textInput(['maxlength' => true]) ?>
	</div>
	
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'inclusions')->widget(Select2::classname(), [
    'data' => $model->items,
    'language' => 'En',
    'options' => ['placeholder' => 'Select Items ...','multiple' => true,],
    'pluginOptions' => [
        'allowClear' => true,
		
    ],
]); ?>
    </div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'packageDes')->textarea(['rows' => 6]) ?>
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