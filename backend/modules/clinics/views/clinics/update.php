<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinics\models\Clinics */

$this->title = 'Update Clinic: ' . $model->clinicName;
$this->params['breadcrumbs'][] = ['label' => 'Clinics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->clinicId, 'url' => ['view', 'id' => $model->clinicId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clinics-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
