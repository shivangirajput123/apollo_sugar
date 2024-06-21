<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Excercise */

$this->title = 'Update Excercise: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Excercises', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->ExcerciseId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="excercise-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
