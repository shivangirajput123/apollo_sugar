<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\GlucoseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="glucose-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'access_token') ?>

    <?= $form->field($model, 'glucosevalue') ?>

    <?= $form->field($model, 'pickdate') ?>

    <?= $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'readingType') ?>

    <?php // echo $form->field($model, 'readingid') ?>

    <?php // echo $form->field($model, 'mealid') ?>

    <?php // echo $form->field($model, 'mealtype') ?>

    <?php // echo $form->field($model, 'mealtime') ?>

    <?php // echo $form->field($model, 'createdDate') ?>

    <?php // echo $form->field($model, 'updatedDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
