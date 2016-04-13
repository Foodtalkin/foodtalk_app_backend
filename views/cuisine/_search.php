<?php
/* @var $this CuisineController */
/* @var $model Cuisine */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cuisineName'); ?>
		<?php echo $form->textField($model,'cuisineName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isDisabled'); ?>
		<?php echo $form->textField($model,'isDisabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disableReason'); ?>
		<?php echo $form->textField($model,'disableReason',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createDate'); ?>
		<?php echo $form->textField($model,'createDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updateDate'); ?>
		<?php echo $form->textField($model,'updateDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createId'); ?>
		<?php echo $form->textField($model,'createId',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updateId'); ?>
		<?php echo $form->textField($model,'updateId',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->