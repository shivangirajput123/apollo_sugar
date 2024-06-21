<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Readingtype */

$this->title = 'Add Readingtype';
$this->params['breadcrumbs'][] = ['label' => 'Readingtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="readingtype-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
