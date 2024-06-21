<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\franchies\models\Franchies */

$this->title = 'Add Franchies';
$this->params['breadcrumbs'][] = ['label' => 'Franchies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchies-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
