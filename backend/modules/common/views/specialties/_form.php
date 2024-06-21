<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Specialties */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="specialties-form">
<div class="box box-primary">
<div class="box-body">
   
    <?php $form = ActiveForm::begin(); ?>

   <div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'speciality_name')->textInput(['maxlength' => true]) ?>
	</div><div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'speciality_title')->textInput(['maxlength' => true]) ?>
	</div><div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'seo_url')->textInput(['maxlength' => true]) ?>
	</div><div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true]) ?>
	</div><div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'metaDescription')->textarea(['rows' => 6]) ?>
	</div><div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'metaKeyword')->textInput(['maxlength' => true]) ?>
	</div><div class="form-group col-lg-6 col-sm-12">
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	
	</div><div class="form-group col-lg-6 col-sm-12">
   

    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'In-active' => 'In-active', ]) ?>

    </div><div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>