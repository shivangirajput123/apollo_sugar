<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Excercise */

$this->title = 'Add Excercise';
$this->params['breadcrumbs'][] = ['label' => 'Excercises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="excercise-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
