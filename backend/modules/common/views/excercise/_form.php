<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Excercise */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="excercise-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

   <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'categoryId')->dropDownList($model->categories,['prompt'=>'Select Category']) ?>
	</div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Url')->textInput(['maxlength' => true]) ?>
	</div>
	
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>
    </div>
	<div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'file')->fileInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-lg-12 col-sm-12">
    <?= $form->field($model, 'Description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
	</div>
	<div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
