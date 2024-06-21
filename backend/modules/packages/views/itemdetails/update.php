<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\ItemDetails */

$this->title = 'Update : ' . $model->itemName;
$this->params['breadcrumbs'][] = ['label' => 'Inclusions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->itemId, 'url' => ['view', 'id' => $model->itemId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-details-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
