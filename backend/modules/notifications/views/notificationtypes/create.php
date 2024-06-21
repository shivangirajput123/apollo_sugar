<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\notifications\models\Notificationtypes */

$this->title = 'Add Notificationtypes';
$this->params['breadcrumbs'][] = ['label' => 'Notificationtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notificationtypes-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
