<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\WebinarsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="webinars-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'webnarId') ?>

    <?= $form->field($model, 'webinarName') ?>

    <?= $form->field($model, 'time') ?>

    <?= $form->field($model, 'doctorId') ?>

    <?= $form->field($model, 'doctorName') ?>

    <?php // echo $form->field($model, 'specialityId') ?>

    <?php // echo $form->field($model, 'specialityName') ?>

    <?php // echo $form->field($model, 'PublishDate') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'PublishStatus') ?>

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
