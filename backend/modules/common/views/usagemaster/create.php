<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Usagemaster */

$this->title = 'Add Usage';
$this->params['breadcrumbs'][] = ['label' => 'Usages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usagemaster-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
