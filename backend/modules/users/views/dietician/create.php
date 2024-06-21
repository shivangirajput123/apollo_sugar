<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Dietician */

$this->title = 'Add Dietician';
$this->params['breadcrumbs'][] = ['label' => 'Dieticians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dietician-create">

   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
