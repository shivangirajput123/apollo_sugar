<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model backend\modules\users\models\Doctors */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Add Slots';
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$url = Url::base();
?>

<div class="doctors-form">
<div class="box box-primary">
<div class="box-body">
  
     <?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]); ?>

   
	<div class="form-group col-lg-3 col-sm-12">
    <?= $form->field($model, 'slotDate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter Slot date ...','value' => $_GET['date']],
	'name' => 'slotDate',
    'pluginOptions' => [
        'autoclose' => true,
		'format' => 'yyyy-mm-dd',
		//'startDate' => $_GET['date'],
    ]
]); ?>
</div>
<div class="form-group col-lg-3 col-sm-12">
    <?= $form->field($model, 'endDate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter Slot date ...','value' => $_GET['date']],
	'name' => 'endDate',
    'pluginOptions' => [
        'autoclose' => true,
		'format' => 'yyyy-mm-dd',
		'startDate' => $_GET['date'],
    ]
]); ?>
</div>
	<div class="form-group col-lg-3 col-sm-12">
    <?= $form->field($model, 'timings')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="form-group col-lg-3 col-sm-12">
    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>
    </div>
	 <div class="form-group col-lg-3 col-sm-12">
    <?= $form->field($model, 'slots',['enableAjaxValidation' => true])->widget(MultipleInput::className(), [
    'max' => 100,
    'columns' => [
    		[
    		'name' => 'slotId',
    		'type' => 'hiddenInput'
    		],    		
    		[
    		'name'  => 'slotTime',
    		'type' => 'textinput',
    	//	'title' => 'slotTime',    		
    		'options' => [
    			
    				'placeholder' => 'Please Enter slotTime',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		]
    		
    		    		
        
    ]
 ]) ?>
</div>
	<?php if($model->slots == [])
	{?>
	 <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
	<?php }else{?>
	
	 <div class="form-group col-lg-12 col-sm-12">
        <button class='btn btn-primary' disabled>Already Slots Available</button>
    </div>
	<?php }?>
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<?php $url = Yii::$app->urlManager->createUrl(['users/doctors/getslots']) ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
  $('#slots-slotdate').on('change',function(){
	  var date =  $('#slots-slotdate').val();
	  window.location.href='<?= Url::to(['doctors/slots','id'=>$_GET['id']])?>&date='+date+'&enddate='+date;
	 
  });
  
  
  $('#slots-duration').on('change',function(){
		var duration = $(this).val();
		var timings = $('#slots-timings').val();
		if(timings != '' &&  duration != ''){
			$.ajax({
                 type: "GET",
                 url: '<?php echo $url;?>',
                 data: {duration:duration,timings:timings}  ,
                 success:function(data) 
                 {	 
				   $('.multiple-input').html(data);
                  }
                });
		}
		
	});
	$('#slots-timings').on('change',function(){
		var timings = $(this).val();
		var duration = $('#slots-duration').val();
		if(timings != '' &&  duration != ''){
			$.ajax({
                 type: "GET",
                 url: '<?php echo $url;?>',
                 data: {duration:duration,timings:timings}  ,
                 success:function(data) 
                 {	 
				   $('.multiple-input').html(data);
                  }
                });
		}
		
	});
});

</script>