<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Glucose */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="glucose-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'access_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'glucosevalue')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pickdate')->textInput() ?>

    <?= $form->field($model, 'time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'readingType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'readingid')->textInput() ?>

    <?= $form->field($model, 'mealid')->textInput() ?>

    <?= $form->field($model, 'mealtype')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mealtime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createdDate')->textInput() ?>

    <?= $form->field($model, 'updatedDate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
