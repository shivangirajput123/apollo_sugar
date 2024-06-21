<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Medicinemaster */

$this->title = 'Add Medicine';
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicinemaster-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
