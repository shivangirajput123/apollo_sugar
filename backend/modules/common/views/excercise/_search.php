<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\ExcerciseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="excercise-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ExcerciseId') ?>

    <?= $form->field($model, 'categoryName') ?>

    <?= $form->field($model, 'categoryId') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'Url') ?>

    <?php // echo $form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'Status') ?>

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
