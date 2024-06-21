<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\modules\packages\models\Plans */

$this->title = "Program Details";
$this->params['breadcrumbs'][] = ['label' => 'Sugar Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Program', 'url' => ['update', 'id' => $_GET['id']]];
$this->params['breadcrumbs'][] = ['label' => 'Program Suggestions', 'url' => ['plansuggestion', 'id' => $_GET['id']]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="plans-view">
<div class="box box-primary">
<div class="box-body">
	
    <?php $form = ActiveForm::begin([]); ?>
	<div class="form-group col-lg-4 col-sm-12">
    <?= $form->field($model, 'PlanName')->textInput(['maxlength' => true,'disabled'=>true]) ?>
	</div>
	<div class="form-group col-lg-4 col-sm-12">
    <?= $form->field($model, 'inclusions')->textInput(['maxlength' => true,'disabled'=>true]) ?>
	</div>
	<div class="form-group col-lg-4 col-sm-12">
    <?= $form->field($model, 'newitems')->textInput(['maxlength' => true,'disabled'=>true]) ?>
	</div>
	<div class="form-group col-lg-4 col-sm-12">
    <?= $form->field($model, 'StartDate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter birth date ...','value'=>$model->StartDate],
    'pluginOptions' => [
        'autoclose' => true,
		'format'=>'yyyy-mm-dd',
		'startDate'=>date('Y-m-d'),'disabled'=>true
    ]
]); ?>
	</div>
	
	
	<div class="form-group col-lg-2 col-sm-12">
    <?= $form->field($model, 'duration')->textInput(['maxlength' => true,'disabled'=>true]) ?>
	</div>
	<div class="form-group col-lg-2 col-sm-12">
    <?= $form->field($model, 'discount')->textInput(['disabled'=>true]) ?>
	</div>
	<div class="form-group col-lg-2 col-sm-12">
    <?= $form->field($model, 'Price')->textInput(['maxlength' => true,'disabled'=>true]) ?>
	</div>
	<div class="form-group col-lg-2 col-sm-12">
    <?= $form->field($model, 'offerPrice')->textInput(['maxlength' => true,'disabled'=>true]) ?>
	</div>
	
	

    <?php ActiveForm::end(); ?>
	
	 <?php $newform = ActiveForm::begin([
    'enableAjaxValidation'      => false,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]); ?>
<div class="form-group col-lg-10 col-sm-12">
    <?= $newform->field($newmodel, 'details',['enableAjaxValidation' => true])->widget(MultipleInput::className(), [
    'max' => 50,
    'columns' => [
    		[
    		'name' => 'plandetailId',
    		'type' => 'hiddenInput'
    		],    		
    		[
    		'name'  => 'day',
			'title'=>'Start Day',
    		'type' => 'textinput',
    	//	'title' => 'slotTime',    		
    		'options' => [
    			
    				'placeholder' => '',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],
		   [
    		'name'  => 'endday',
			'title'=>'End Day',
    		'type' => 'textinput',
    	//	'title' => 'slotTime',    		
    		'options' => [
    			
    				'placeholder' => '',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'text',
			'title'=>'Service',
    		//'type' => 'dropDownList',			
    		'type' => Select2::className(),
			//'items'=>$inclusions,
			'options' => 
			[
			     'data' => $inclusions,
                 'pluginOptions' => 
				 [
                        'multiple'=>true
                 ],
            ],
    		'enableError' =>true,
    		]
    		
    		    		
        
    ]
 ])->label(false) ?>
</div>
		
	 <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success' ,'id'=>"submit"]) ?>
    </div>

    <?php ActiveForm::end(); ?>

  
</div>
</div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
  let count = '<?php echo $count;?>';
  let max = count;
  jQuery('#w1').on('afterInit', function(){	  
    console.log('calls on after initialization event');
	    setDatevalue(0)
	}).on('beforeAddRow', function(e, row, currentIndex) {
		console.log(currentIndex+'calls on before add row event');
	}).on('afterAddRow', function(e, row, currentIndex) {
		setDatevalue(currentIndex)
	}).on('beforeDeleteRow', function(e, row, currentIndex){
		// row - HTML container of the current row for removal.
		// For TableRenderer it is tr.multiple-input-list__item
		console.log(currentIndex+'calls on before remove row event.');
		return confirm('Are you sure you want to delete row?')
	}).on('afterDeleteRow', function(e, row, currentIndex){
		max = (parseInt(max)-1);
		//alert(max);
	}).on('afterDropRow', function(e, item){       
		console.log(currentIndex+'calls on after drop row', item);
	});
		
	/*$("#submit").click(function(e){ 
	    e.preventDefault();
		var error = 1;
		for(let i=0;i<max;i++){
			error = setDatevalue(i)
		}
		//alert(error);
		if(error)
		{
			$("#w1").submit(); 
		}// Submit the form
    });*/
});
/*function setDatevalue(i){
	 var error = 1;
	 var day =  $('#plandetails-details-'+i+'-day').val();
	 var endday =  $('#plandetails-details-'+i+'-endday').val();	 
	 if(!$.isNumeric(day))
	 {
		 error = 0;
		 alert('Start Day Must be Integer');
		 
	 }	
     else if(!$.isNumeric(endday))
	 {
		 error = 0;
		 alert('End Day Must be Integer');
		 
	 }		 
     return error;
}*/

</script>	