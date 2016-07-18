<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'restaurant-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'restaurantName'); ?>
		<?php echo $form->textField($model,'restaurantName',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'restaurantName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contactName'); ?>
		<?php echo $form->textField($model,'contactName',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'contactName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'country'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>
	
	
	<div class="row">
		<?php
		$model->region = isset($_SESSION['region'])? $_SESSION['region'] : '';
		echo $form->labelEx($model,'region'); ?>
		<?php echo $form->dropDownList($model, 'region', CHtml::listData(Region::model()->findAll(), 'name', 'name'), array('empty'=>'Select a Region'));
		echo $form->error($model,'region'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'area'); ?>
		<?php echo $form->textField($model,'area',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'area'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postcode'); ?>
		<?php echo $form->textField($model,'postcode',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'postcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude'); ?>
		<?php echo $form->error($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude'); ?>
		<?php echo $form->error($model,'longitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone1'); ?>
		<?php echo $form->textField($model,'phone1',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone2'); ?>
		<?php echo $form->textField($model,'phone2',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone2'); ?>
	</div>

	<div class="checkbox">
		<?php 
		
		echo $form->checkBox($model, 'suggested');
		echo $form->labelEx($model,'suggested');
		echo $form->error($model,'suggested');
		
		echo $form->checkBox($model, 'homeDelivery'); 
		echo $form->labelEx($model,'homeDelivery'); 
		echo $form->error($model,'homeDelivery');
		
		echo $form->checkBox($model, 'nonVeg');
		echo $form->labelEx($model,'nonVeg');
		echo $form->error($model,'nonVeg');
		
		echo $form->checkBox($model, 'dineIn');
		echo $form->labelEx($model,'dineIn');
		echo $form->error($model,'dineIn');
		
		echo $form->checkBox($model, 'outdoorSeating');
		echo $form->labelEx($model,'outdoorSeating');
		echo $form->error($model,'outdoorSeating');
				
		echo $form->checkBox($model, 'airConditioned');
		echo $form->labelEx($model,'airConditioned');
		echo $form->error($model,'airConditioned');
		
		echo $form->checkBox($model, 'fullBar');
		echo $form->labelEx($model,'Bar Avilable');
		echo $form->error($model,'fullBar');
		
		echo $form->checkBox($model, 'microbrewery');
		echo $form->labelEx($model,'microbrewery');
		echo $form->error($model,'microbrewery');
		
		echo $form->checkBox($model, 'sheesha');
		echo $form->labelEx($model,'sheesha');
		echo $form->error($model,'sheesha');
		
		echo $form->checkBox($model, 'wifi');
		echo $form->labelEx($model,'wifi');
		echo $form->error($model,'wifi');
		
		echo $form->checkBox($model, 'liveMusic');
		echo $form->labelEx($model,'liveMusic');
		echo $form->error($model,'liveMusic');
		
		 ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'priceRange'); ?>
		<?php echo $form->textField($model,'priceRange',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'priceRange'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'timing'); ?>
		<?php echo $form->textField($model,'timing',array('size'=>100,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'timing'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'webAddress'); ?>
		<?php echo $form->textField($model,'webAddress',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'webAddress'); ?>
	</div>

	<?php if($model->image) echo CHtml::image(Yii::app()->baseUrl . '/images/restaurant/thumb/' . $model->image) . '<br/>'; ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image'); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<div class="checkbox">
	<?php
		echo $form->checkBox($model, 'isActivated');
		echo $form->labelEx($model,'Active');
		echo $form->error($model,'isActivated');
	?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->