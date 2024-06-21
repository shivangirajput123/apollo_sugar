<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\article\models\Articles */

$this->title = 'Update : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->articleId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="articles-update">

  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
