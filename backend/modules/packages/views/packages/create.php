<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Packages */

$this->title = 'Add Service';
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packages-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
