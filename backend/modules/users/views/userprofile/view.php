<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Userprofile */

$this->title = $model->profileId;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="userprofile-view">

   <div class="box box-primary">
<div class="box-body">
   

   

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'profileId',
            'firstName',
            'userId',
            'lastName',
            'gender',
            'profilePic',
            'DOB',
            'weight',
            'height',
            'age',
            'familyhistory',
            'glucosescore',
            'diabeticcondition',
            'createdDate',
            'updatedDate',
            'access_token',
        ],
    ]) ?>

</div>
</div>
</div>