<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Medicinemaster */

$this->title = 'Update Medicines: ' . $model->medicineName;
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->medicineId, 'url' => ['view', 'id' => $model->medicineId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medicinemaster-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
