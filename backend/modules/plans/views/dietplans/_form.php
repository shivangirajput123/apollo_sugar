<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\Dietplans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dietplans-form">

    <div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]); ?>

   <div class="form-group col-lg-12 col-sm-12">
        <?= $form->field($model, 'times')->widget(MultipleInput::className(), [
    'max' => 6,
	
    'columns' => [
    		[
    		'name' => 'planId',
    		'type' => 'hiddenInput'
    		],   
			
    		[
    		'name'  => 'time',
    		'type' => 'textinput',
    	    'title' => 'Time',    		
    		'options' => [
    			
    				'placeholder' => 'time',
    				'style' => array('width'=>'100%')
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'mealtype',
    		'type' => 'dropDownList',
    	    'title' => 'Meal Type',
			'items'=>$model->mealtypes,    		
    		'options' => [
    			
    				'placeholder' => 'mealtype',
    				'style' => array('width'=>'100%')
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'items',
    		'type' => MultipleInput::className(),
    		'title' => 'items',    		
    		'enableError' =>true,
			'options' => 
			[    			
    			'max' => 4, 
				 'columns' => [
						[
						'name' => 'dietplanId',
						'type' => 'hiddenInput'
						], 
						[
							'name'  => 'item',
							'type' => 'dropDownList',
							
							//'title' => 'Time',
							'items'=>$model->fooditems,
							'options' => [
										'style' => array('width'=>'100%'),
										'class'=>'fooditems',
								
								],
							
							'enableError' =>true,
							],
							[
								'name'  => 'quantity',
								'type' => 'textinput',
							   // 'title' => 'Meal Type',    		
								'options' => [
									
										'placeholder' => 'quantity',
										'style' => array('width'=>'100%'),
										'class'=>'quantity'
								
								],
								'enableError' =>true,
							],
							[
								'name'  => 'calories',
								'type' => 'textinput',
							   // 'title' => 'Meal Type',    		
								'options' => [
									
										'placeholder' => 'calories',
										'style' => array('width'=>'100%')
								
								],
								'enableError' =>true,
							],
						
				],			
    		],
    		],
			
    		
    		    		
        
    ]
 ])->label(false); ?>
		
	</div>

   

    
   
   <div class="form-group col-lg-12 col-sm-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php $url = Yii::$app->urlManager->createUrl(['users/doctors/getslots']) ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
   jQuery('#w1').on('afterInit', function(){	  
    console.log('calls on after initialization event');
	}).on('beforeAddRow', function(e, row, currentIndex) {
		console.log(currentIndex+'calls on before add row event');
	}).on('afterAddRow', function(e, row, currentIndex) {
		console.log(currentIndex+'calls on after add row event');
	}).on('beforeDeleteRow', function(e, row, currentIndex){
		// row - HTML container of the current row for removal.
		// For TableRenderer it is tr.multiple-input-list__item
		console.log(currentIndex+'calls on before remove row event.');
		return confirm('Are you sure you want to delete row?')
	}).on('afterDeleteRow', function(e, row, currentIndex){
		console.log(currentIndex+'calls on after remove row event');
		console.log(row);
	}).on('afterDropRow', function(e, item){       
		console.log(currentIndex+'calls on after drop row', item);
	});

	
});

</script>