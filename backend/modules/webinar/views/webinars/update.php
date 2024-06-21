<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\Webinars */

$this->title = 'Update Webinars: ' . $model->webinarName;
$this->params['breadcrumbs'][] = ['label' => 'Webinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->webinarName, 'url' => ['view', 'id' => $model->webnarId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="webinars-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
