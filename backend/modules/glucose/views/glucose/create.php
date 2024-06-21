<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Glucose */

$this->title = 'Create Glucose';
$this->params['breadcrumbs'][] = ['label' => 'Glucoses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glucose-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
