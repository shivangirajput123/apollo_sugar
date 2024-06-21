<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Superadmin */

$this->title = 'Update : ' . $model->firstName;
$this->params['breadcrumbs'][] = ['label' => 'Superadmins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->adminUserId, 'url' => ['view', 'id' => $model->adminUserId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="superadmin-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
