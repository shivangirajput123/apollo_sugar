<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\Webinarenrolls */

$this->title = 'Update Webinarenrolls: ' . $model->enrolId;
$this->params['breadcrumbs'][] = ['label' => 'Webinarenrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->enrolId, 'url' => ['view', 'id' => $model->enrolId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="webinarenrolls-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
