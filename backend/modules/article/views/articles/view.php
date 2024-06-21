<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\article\models\Articles */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="articles-view">
<div class="box box-primary">
<div class="box-body">
    

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->articleId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->articleId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'articleId',            
            'categoryName',
            'title',
			[
				'attribute'=>'Description',
				'value' =>  function($data){
					return str_replace("&nbsp;", "",strip_tags($data->Description));
				},            
			],
           // 'Description:ntext',
            'Url:url',
           
            'Status',
            'createdBy',
            'updatedBy',
            'createdDate',
            'updatedDate',
            'ipAddress',
        ],
    ]) ?>

</div>
</div>
</div>