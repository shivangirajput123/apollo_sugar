<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\modules\clinics\models\Clinics */

$this->title = "Approval";
$this->params['breadcrumbs'][] = ['label' => 'Plan Approvals', 'url' => ['planapproval']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<style>
body
{
  background-color: #f7f7ff;
}
.radius-10 
{
    border-radius: 10px !important;
}
.border-info 
{
    border-left: 5px solid  #0dcaf0 !important;
}
.border-danger 
{
    border-left: 5px solid  #fd3550 !important;
}
.border-success 
{
    border-left: 5px solid  #15ca20 !important;
}
.border-warning 
{
    border-left: 5px solid  #ffc107 !important;
}
.card 
{
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e4e4e4;
    border-radius: .25rem;
    margin-bottom: 1.5rem;
   /* box-shadow: 1px 2px 2px 0 rgb(218 218 253 / 65%), 1px 2px 2px 1px rgb(206 206 238 / 54%); */
	margin-right:6px;
	text-align: center;
	
}

.card-body
{
	height:100px;	
	margin-left:50px;
	margin-top:30px;
}
.bg-gradient-scooter 
{
    background: #17ead9;
    background: -webkit-linear-gradient( 
45deg
 , #17ead9, #6078ea)!important;
    background: linear-gradient( 
45deg
 , #17ead9, #6078ea)!important;
}
.widgets-icons-2 
{
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ededed;
    font-size: 27px;
    border-radius: 10px;
}
.rounded-circle {
    border-radius: 50%!important;
}
.text-white {
    color: #fff!important;
}
.ms-auto {
    margin-left: auto!important;
}
.bg-gradient-bloody 
{
    background: #f54ea2;
    background: -webkit-linear-gradient( 
45deg
 , #f54ea2, #ff7676)!important;
    background: linear-gradient( 
45deg
 , #f54ea2, #ff7676)!important;
}
.bg-gradient-ohhappiness 
{
    background: #00b09b;
    background: -webkit-linear-gradient( 
45deg
 , #00b09b, #96c93d)!important;
    background: linear-gradient( 
45deg
 , #00b09b, #96c93d)!important;
}
.bg-gradient-blooker 
{
    background: #ffdf40;
    background: -webkit-linear-gradient( 
45deg
 , #ffdf40, #ff8359)!important;
background: linear-gradient( 
45deg
 , #ffdf40, #ff8359)!important;
}
.col-lg-3 
{
    width: 23% !important;
}
</style>
<div class="clinics-view">
	<div class="card col-lg-6 col-sm-12">  
			<p>Customer Name:<?= $model['firstName'];?></p>
			<p>Enrolled Date:<?= date('d-M-Y',strtotime($model['createdDate']));?></p>
			<p>MobileNumber:<?= $model['username'];?></p> 
			<p>Program Name:<?= $model['planName'];?></p> 
			
	</div> 
</div>

<div class="doctors-form">
<div class="box box-primary">

 <?php $form = ActiveForm::begin(); ?>
	  <div class="form-group col-lg-6 col-sm-12">
         <?= $form->field($newmodel, 'doctorId')->dropDownList($newmodel->doctors,['prompt'=>'Auto'])  ?>
       </div>
	   <div class="form-group col-lg-6 col-sm-12">
         <?= $form->field($newmodel, 'dieticianId')->dropDownList($newmodel->dieticians,['prompt'=>'Auto'])  ?>
       </div>
	   <div class="form-group col-lg-6 col-sm-12">
         <?= $form->field($newmodel, 'txnId')->checkbox(['uncheck' => 'Disabled', 'value' => 'Active']);  ?>
       </div>
	  <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
      </div>

    <?php ActiveForm::end(); ?>
	</div>
	</div>