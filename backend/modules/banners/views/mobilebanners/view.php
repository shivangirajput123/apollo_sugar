<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Mobilebanners */

$this->title = $model->baner_name;
$this->params['breadcrumbs'][] = ['label' => 'Mobilebanners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$url = Url::base();
$baseurl = str_replace('backend','frontend',$url);

?>
<div class="mobilebanners-view">
<div class="box box-primary">
<div class="box-body">
   
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'baner_name',
            'itemcode',
             [
            'attribute'=>'baner_image',
            'value' =>  (Html::img($baseurl.'/'.$model->baner_image, ['alt'=>'some', 'class'=>'thing', 'height'=>'100px', 'width'=>'100px'])),
            'format' => ['raw'],
        ],
            'status',
            'priority',
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'php:d-M-Y H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'php:d-M-Y H:i:s'],
            ],
            
        ],
    ]) ?>
    

</div>
    </div>
    </div>