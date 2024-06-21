<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Coach */

$this->title = 'Add Coach';
$this->params['breadcrumbs'][] = ['label' => 'Coaches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
