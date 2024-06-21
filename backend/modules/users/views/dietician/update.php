<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Dietician */

$this->title = 'Update Dietician: ' . $model->dieticianName;
$this->params['breadcrumbs'][] = ['label' => 'Dieticians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dieticianId, 'url' => ['view', 'id' => $model->dieticianId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dietician-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
