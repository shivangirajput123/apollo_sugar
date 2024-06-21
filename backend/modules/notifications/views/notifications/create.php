<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\notifications\models\Notifications */

$this->title = 'Add Notification';
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
