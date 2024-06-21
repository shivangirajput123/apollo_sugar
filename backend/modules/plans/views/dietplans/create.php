<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\Dietplans */

$this->title = 'Add Dietplan To Patient';
$this->params['breadcrumbs'][] = ['label' => 'Dietplans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dietplans-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
