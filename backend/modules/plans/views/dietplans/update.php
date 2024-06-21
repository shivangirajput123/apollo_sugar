<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\Dietplans */

$this->title = 'Update Dietplans: ' . $model->planId;
$this->params['breadcrumbs'][] = ['label' => 'Dietplans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->planId, 'url' => ['view', 'id' => $model->planId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dietplans-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
