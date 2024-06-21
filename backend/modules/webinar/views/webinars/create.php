<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\Webinars */

$this->title = 'Add Webinar';
$this->params['breadcrumbs'][] = ['label' => 'Webinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webinars-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
