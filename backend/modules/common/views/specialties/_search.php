<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\SpecialtiesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="specialties-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'speciality_id') ?>

    <?= $form->field($model, 'speciality_name') ?>

    <?= $form->field($model, 'speciality_title') ?>

    <?= $form->field($model, 'seo_url') ?>

    <?= $form->field($model, 'metaTitle') ?>

    <?php // echo $form->field($model, 'metaDescription') ?>

    <?php // echo $form->field($model, 'metaKeyword') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'cityId') ?>

    <?php // echo $form->field($model, 'cityName') ?>

    <?php // echo $form->field($model, 'locationId') ?>

    <?php // echo $form->field($model, 'locatonName') ?>

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
