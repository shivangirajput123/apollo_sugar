<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Leavetype */

$this->title = 'Add Leavetype';
$this->params['breadcrumbs'][] = ['label' => 'Leavetypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leavetype-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
