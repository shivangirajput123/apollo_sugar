<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\roles\models\Roles */

$this->title = 'Update Role: ' . $model->roleName;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->roleName, 'url' => ['view', 'id' => $model->roleId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="roles-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
