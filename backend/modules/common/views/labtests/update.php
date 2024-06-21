<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Labtests */

$this->title = 'Update Labtests: ' . $model->testName;
$this->params['breadcrumbs'][] = ['label' => 'Labtests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->labTestId, 'url' => ['view', 'id' => $model->labTestId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="labtests-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
