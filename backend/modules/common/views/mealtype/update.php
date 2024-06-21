<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Mealtype */

$this->title = 'Update Mealtype: ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Mealtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mealtype-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
