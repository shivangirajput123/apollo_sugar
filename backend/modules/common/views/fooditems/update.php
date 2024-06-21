<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Fooditems */

$this->title = 'Update : ' . $model->itemName;
$this->params['breadcrumbs'][] = ['label' => 'Fooditems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->itemName, 'url' => ['view', 'id' => $model->itemId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fooditems-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
