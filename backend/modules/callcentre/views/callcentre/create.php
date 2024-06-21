<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\callcentre\models\Callcentre */

$this->title = 'Add Callcentre';
$this->params['breadcrumbs'][] = ['label' => 'Callcentres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callcentre-create">

      <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
