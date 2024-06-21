<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\WebinarenrollsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="webinarenrolls-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'enrolId') ?>

    <?= $form->field($model, 'webinarId') ?>

    <?= $form->field($model, 'access_token') ?>

    <?= $form->field($model, 'createdDate') ?>

    <?= $form->field($model, 'ipAddress') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
