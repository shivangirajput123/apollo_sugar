<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Packages */

$this->title = 'Update: ' . $model->packageName;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->packageName, 'url' => ['view', 'id' => $model->packageId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="packages-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
