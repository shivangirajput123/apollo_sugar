<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\notifications\models\Notificationtypes */

$this->title = 'Update Notificationtypes: ' . $model->notificationTypeId;
$this->params['breadcrumbs'][] = ['label' => 'Notificationtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->notificationTypeId, 'url' => ['view', 'id' => $model->notificationTypeId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notificationtypes-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
