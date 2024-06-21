<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Plans */

$this->title = 'Add Program';
$this->params['breadcrumbs'][] = ['label' => 'Sugar Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
