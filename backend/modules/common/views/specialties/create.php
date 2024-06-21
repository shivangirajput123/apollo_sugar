<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Specialties */

$this->title = 'Add Speciality';
$this->params['breadcrumbs'][] = ['label' => 'Specialities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specialties-create">

  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
