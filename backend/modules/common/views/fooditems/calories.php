<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\Fooditemdetails */
/* @var $form ActiveForm */
$this->title = 'Calories';
$this->params['breadcrumbs'][] = ['label' => 'Fooditems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fooditems-form">
<div class="box box-primary">
<div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

	<div class="form-group col-lg-12 col-sm-12">
        <?= $form->field($model, 'calories')->widget(MultipleInput::className(), [
    'max' => 3,
    'columns' => [
    		[
    		'name' => 'fooditemId',
    		'type' => 'hiddenInput'
    		],   
            [
    		'name'  => 'quantity',
    		'type' => 'dropDownList',
    		'title' => 'quantity', 
			'items' => [
                        'medium' => 'medium',
                        'large' => 'large',
                        'small' => 'small'
                    ],   		
    		'options' => [
    			
    				'placeholder' => 'quantity',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],			
    		[
    		'name'  => 'cal',
    		'type' => 'textinput',
         	'title' => 'cal',    		
    		'options' => [
    			
    				'placeholder' => 'calories',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'carbohydrates',
    		'type' => 'textinput',
    		'title' => 'carbohydrates',    		
    		'options' => [
    			
    				'placeholder' => 'carbohydrates',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'proteins',
    		'type' => 'textinput',
    		'title' => 'proteins',    		
    		'options' => [
    			
    				'placeholder' => 'proteins',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'fat',
    		'type' => 'textinput',
    		'title' => 'fat',    		
    		'options' => [
    			
    				'placeholder' => 'fat',
    				'style' => array('width'=>'100%')
    				
    			
    		
    		],
    		'enableError' =>true,
    		],
			[
    		'name'  => 'fiber',
    		'type' => 'textinput',
    		'title' => 'fiber',    		
    		'options' => [
    			
    				'placeholder' => 'fiber',
    				'style' => array('width'=>'100%')
    		
    		],
    		'enableError' =>true,
    		]
    		
    		    		
        
    ]
 ])->label(false); ?>
		</div>
        <div class="form-group col-lg-12 col-sm-12">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
  
	

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>