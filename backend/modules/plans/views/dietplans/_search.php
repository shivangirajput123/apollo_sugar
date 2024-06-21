<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\DietplansSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dietplans-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'planId') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'time') ?>

    <?= $form->field($model, 'mealtypeId') ?>

    <?= $form->field($model, 'mealtype') ?>

    <?php // echo $form->field($model, 'itemId') ?>

    <?php // echo $form->field($model, 'itemName') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'calories') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'createdDate') ?>

    <?php // echo $form->field($model, 'updatedDate') ?>

    <?php // echo $form->field($model, 'ipAddress') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
