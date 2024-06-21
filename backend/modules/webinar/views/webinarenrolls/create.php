<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\webinar\models\Webinarenrolls */

$this->title = 'Create Webinarenrolls';
$this->params['breadcrumbs'][] = ['label' => 'Webinarenrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webinarenrolls-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
