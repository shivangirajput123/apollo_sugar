<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Mobilebanners */

$this->title = 'Add Banner';
$this->params['breadcrumbs'][] = ['label' => 'Mobilebanners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobilebanners-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
