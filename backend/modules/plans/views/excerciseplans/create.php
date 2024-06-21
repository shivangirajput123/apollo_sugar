<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\Excerciseplans */

$this->title = 'Add Excercise To Patient';
$this->params['breadcrumbs'][] = ['label' => 'User Profiles', 'url' => ['/users/userprofile/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="excerciseplans-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
