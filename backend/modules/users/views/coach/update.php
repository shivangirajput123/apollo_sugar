<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Coach */

$this->title = 'Update Coach: ' . $model->coachName;
$this->params['breadcrumbs'][] = ['label' => 'Coaches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coachId, 'url' => ['view', 'id' => $model->coachId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coach-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
