<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\ItemDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'itemId') ?>

    <?= $form->field($model, 'itemName') ?>

    <?= $form->field($model, 'itemCode') ?>

    <?= $form->field($model, 'aliasName') ?>

    <?= $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'offerPrice') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'cityId') ?>

    <?php // echo $form->field($model, 'cityName') ?>

    <?php // echo $form->field($model, 'locationId') ?>

    <?php // echo $form->field($model, 'locationName') ?>

    <?php // echo $form->field($model, 'itemDescription') ?>

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
