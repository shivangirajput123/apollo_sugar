<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\franchies\models\Franchies */

$this->title = 'Update Franchies: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Franchies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="franchies-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
