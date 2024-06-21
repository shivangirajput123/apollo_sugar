<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Fooditems */

$this->title = 'Add Item';
$this->params['breadcrumbs'][] = ['label' => 'Fooditems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fooditems-create">
  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
