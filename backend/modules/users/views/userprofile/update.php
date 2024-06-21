<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Userprofile */

$this->title = 'Update Customer: ' . $model->profileId;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->profileId, 'url' => ['view', 'id' => $model->profileId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="userprofile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
