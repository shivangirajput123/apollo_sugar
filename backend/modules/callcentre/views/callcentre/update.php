<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\callcentre\models\Callcentre */

$this->title = 'Update Callcentre: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Callcentres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="callcentre-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
