<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\modules\callcentre\models\Callcentre */

$this->title = 'Booking Status';
$this->params['breadcrumbs'][] = ['label' => 'Upcoming Lab tests', 'url' => ['tests','token'=>$model->access_token]];

?>
<div class="callcentre-form">
<div class="box box-primary">
<div class="box-body">
  
    <?php $form = ActiveForm::begin(); ?>

  
 <div class="form-group col-lg-6 col-sm-12">

    <?= $form->field($model, 'bookingStatus')->dropDownList(['Completed' => 'Completed','Cancel' => 'Cancel','Sample Received'=>'Sample Received','Reports Generated'=>'Reports Generated']) ?>
</div>
	 <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

