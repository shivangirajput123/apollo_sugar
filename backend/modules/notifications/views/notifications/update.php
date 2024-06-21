<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\notifications\models\Notifications */

$this->title = 'Update : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notifications-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
