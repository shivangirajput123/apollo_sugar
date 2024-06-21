<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinics\models\Clinics */

$this->title = 'Add Clinic';
$this->params['breadcrumbs'][] = ['label' => 'Clinics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clinics-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
