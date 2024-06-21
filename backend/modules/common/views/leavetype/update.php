<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Leavetype */

$this->title = 'Update: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Leavetypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="leavetype-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
