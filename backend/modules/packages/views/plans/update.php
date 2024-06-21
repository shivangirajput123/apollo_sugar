<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Plans */

$this->title = 'Update: ' . $model->PlanName;
$this->params['breadcrumbs'][] = ['label' => 'Sugar Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PlanName, 'url' => ['view', 'id' => $model->planId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="plans-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
