<?php
/* @var $this SessionController */
/* @var $model Session */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'session-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'sessionId'); ?>
		<?php echo $form->textField($model,'sessionId',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'sessionId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userId'); ?>
		<?php echo $form->textField($model,'userId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'userId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userName'); ?>
		<?php echo $form->textField($model,'userName',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'userName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->textField($model,'role',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deviceToken'); ?>
		<?php echo $form->textField($model,'deviceToken',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'deviceToken'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deviceBadge'); ?>
		<?php echo $form->textField($model,'deviceBadge',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'deviceBadge'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'timestamp'); ?>
		<?php echo $form->textField($model,'timestamp'); ?>
		<?php echo $form->error($model,'timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->