<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\CoachSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coach-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coachId') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'cityId') ?>

    <?= $form->field($model, 'cityName') ?>

    <?= $form->field($model, 'locationId') ?>

    <?php // echo $form->field($model, 'locationName') ?>

    <?php // echo $form->field($model, 'coachName') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'coachDesription') ?>

    <?php // echo $form->field($model, 'profileImage') ?>

    <?php // echo $form->field($model, 'experience') ?>

    <?php // echo $form->field($model, 'qualification') ?>

    <?php // echo $form->field($model, 'membership') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'createdDate') ?>

    <?php // echo $form->field($model, 'updatedDate') ?>

    <?php // echo $form->field($model, 'ipAddress') ?>

    <?php // echo $form->field($model, 'metaTitle') ?>

    <?php // echo $form->field($model, 'metaDescription') ?>

    <?php // echo $form->field($model, 'metaKeywords') ?>

    <?php // echo $form->field($model, 'seo_url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
