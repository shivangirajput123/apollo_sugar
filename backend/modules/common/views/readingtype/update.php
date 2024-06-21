<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Readingtype */

$this->title = 'Update Readingtype: ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Readingtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="readingtype-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
