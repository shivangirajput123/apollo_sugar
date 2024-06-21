<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Mealtype */

$this->title = 'Add Mealtype';
$this->params['breadcrumbs'][] = ['label' => 'Mealtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mealtype-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
