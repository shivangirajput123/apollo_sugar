<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Doctors */

$this->title = 'Update Doctors: ' . $model->doctorName;
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doctorId, 'url' => ['view', 'id' => $model->doctorId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doctors-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
