<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Durations */

$this->title = 'Update Durations: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Durations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->durationId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="durations-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
