<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Specialties */

$this->title = 'Update Specialties: ' . $model->speciality_name;
$this->params['breadcrumbs'][] = ['label' => 'Specialties', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->speciality_name, 'url' => ['view', 'id' => $model->speciality_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="specialties-update">   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
