<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-score-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'facebookId'); ?>
		<?php echo $form->textField($model,'facebookId',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'facebookId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'avilablePoints'); ?>
		<?php echo $form->textField($model,'avilablePoints'); ?>
		<?php echo $form->error($model,'avilablePoints'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'totalPoints'); ?>
		<?php echo $form->textField($model,'totalPoints'); ?>
		<?php echo $form->error($model,'totalPoints'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'score'); ?>
		<?php echo $form->textField($model,'score'); ?>
		<?php echo $form->error($model,'score'); ?>
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