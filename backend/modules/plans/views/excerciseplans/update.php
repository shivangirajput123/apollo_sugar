<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\Excerciseplans */

$this->title = 'Update Excerciseplans: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Excerciseplans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->explanId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="excerciseplans-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
