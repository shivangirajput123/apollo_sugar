<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Durations */

$this->title = 'Add Duration';
$this->params['breadcrumbs'][] = ['label' => 'Durations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="durations-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
