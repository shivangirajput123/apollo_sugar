<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Portions */

$this->title = 'Add Portion';
$this->params['breadcrumbs'][] = ['label' => 'Portions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portions-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
