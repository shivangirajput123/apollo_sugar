<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Usagemaster */

$this->title = 'Update Usagemaster: ' . $model->usageName;
$this->params['breadcrumbs'][] = ['label' => 'Usages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usageId, 'url' => ['view', 'id' => $model->usageId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="usagemaster-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
