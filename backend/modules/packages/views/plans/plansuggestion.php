<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Plansuggestions */
/* @var $form ActiveForm */
$this->title = 'Program Suggestions';
$this->params['breadcrumbs'][] = ['label' => 'Sugar Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Program', 'url' => ['update', 'id' => $_GET['id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plansuggestion">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
		<div class="form-group col-lg-6 col-sm-12">
        <?= $form->field($model, 'hba1ccondition')->dropDownList([''=>'select','<'=>'LessThan','>'=>'GreaterThan']) ?>
		</div>
		<div class="form-group col-lg-6 col-sm-12">
        <?= $form->field($model, 'hba1cvalue')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-6 col-sm-12">
        <?= $form->field($model, 'age')->dropDownList([''=>'select','<'=>'LessThan','>'=>'GreaterThan',]) ?>
		</div>
		<div class="form-group col-lg-6 col-sm-12">
        <?= $form->field($model, 'agevalue')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'diabeticcondtion')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'period')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'managediabetes')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'typicalday')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'explowsugar')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'gender')->dropDownList(['Female'=>'Female','Male'=>'Male','Both'=>'Both']) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'pregnancyStatus')->dropDownList(['No'=>'No','Yes'=>'Yes']) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'preexistingcondtion')->textInput([]) ?>
		</div>
		<div class="form-group col-lg-2 col-sm-12">
        <?= $form->field($model, 'physicalactivity')->dropDownList([''=>'select','No'=>'No','Moderate'=>'Moderate','Regular'=>'Regular']) ?>
		</div>
		<div class="form-group col-lg-12 col-sm-12">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>
<!-- plansuggestion -->
