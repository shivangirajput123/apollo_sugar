<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Portions */

$this->title = 'Update: ' . $model->portionName;
$this->params['breadcrumbs'][] = ['label' => 'Portions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->portionName, 'url' => ['view', 'id' => $model->portionId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="portions-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
