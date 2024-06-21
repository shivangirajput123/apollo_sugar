<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\ItemDetails */

$this->title = 'Add Inclusion';
$this->params['breadcrumbs'][] = ['label' => 'Inclusions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-details-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
