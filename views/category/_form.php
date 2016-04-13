<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'categoryName'); ?>
		<?php echo $form->textField($model,'categoryName',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'categoryName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isDisabled'); ?>
		<?php echo $form->textField($model,'isDisabled'); ?>
		<?php echo $form->error($model,'isDisabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'disableReason'); ?>
		<?php echo $form->textField($model,'disableReason',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'disableReason'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createDate'); ?>
		<?php echo $form->textField($model,'createDate'); ?>
		<?php echo $form->error($model,'createDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updateDate'); ?>
		<?php echo $form->textField($model,'updateDate'); ?>
		<?php echo $form->error($model,'updateDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createId'); ?>
		<?php echo $form->textField($model,'createId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'createId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updateId'); ?>
		<?php echo $form->textField($model,'updateId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'updateId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->