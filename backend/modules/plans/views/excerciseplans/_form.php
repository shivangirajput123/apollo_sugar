<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model backend\modules\plans\models\Excerciseplans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="excerciseplans-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]); ?>
	
	
	
	
   <div class="form-group col-lg-6 col-sm-12">
        <?= $form->field($model, 'times')->widget(MultipleInput::className(), [
    'max' => 6,
	
    'columns' => [
    		[
    		'name' => 'explanId',
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
    		'name'  => 'excercises',
    		'type' => MultipleInput::className(),
    		'title' => 'excercises',    		
    		'enableError' =>true,
			'options' => 
			[    			
    			'max' => 4, 
				 'columns' => [
						[
						'name' => 'explandetId',
						'type' => 'hiddenInput'
						], 
						[
							'name'  => 'excercise',
							'type' => 'dropDownList',
							//'title' => 'Time',
							'items'=>$model->excerciselist,
							
							'enableError' =>true,
							],
						[
							'name'  => 'distance',
							'type' => 'textinput',
							//'title' => 'Time',    		
							'options' => [
								
									'placeholder' => 'distance',
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