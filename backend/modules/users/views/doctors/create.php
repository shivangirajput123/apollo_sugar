<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Doctors */

$this->title = 'Add Doctor';
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctors-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
