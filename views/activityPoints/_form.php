<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-points-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'activityTable'); ?>
		<?php echo $form->textField($model,'activityTable',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'activityTable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activityTitle'); ?>
		<?php echo $form->textField($model,'activityTitle',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'activityTitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'platform'); ?>
		<?php echo $form->textField($model,'platform',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'platform'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activityDesc'); ?>
		<?php echo $form->textArea($model,'activityDesc',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'activityDesc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'points'); ?>
		<?php echo $form->textField($model,'points'); ?>
		<?php echo $form->error($model,'points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'timefactor In minutes'); ?>
		<?php echo $form->textField($model,'timefactor'); ?>
		<?php echo $form->error($model,'timefactor'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'minimum Points'); ?>
		<?php echo $form->textField($model,'minimum'); ?>
		<?php echo $form->error($model,'minimum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maximum Points'); ?>
		<?php echo $form->textField($model,'maximum'); ?>
		<?php echo $form->error($model,'maximum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Points for penality'); ?>
		<?php echo $form->textField($model,'penality'); ?>
		<?php echo $form->error($model,'penality'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->