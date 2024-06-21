<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\roles\models\Roles */

$this->title = 'Add Role';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
