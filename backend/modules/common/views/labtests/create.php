<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Labtests */

$this->title = 'Add Test';
$this->params['breadcrumbs'][] = ['label' => 'Labtests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="labtests-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
