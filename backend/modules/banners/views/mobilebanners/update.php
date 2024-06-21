<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\banners\models\Mobilebanners */

$this->title = 'Update Banner: ' . $model->baner_name;
$this->params['breadcrumbs'][] = ['label' => 'Mobilebanners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->baner_name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mobilebanners-update">

  
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
