<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Reasons */

$this->title = 'Add Reason';
$this->params['breadcrumbs'][] = ['label' => 'Reasons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reasons-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
