<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'userId'); ?>
		<?php echo $form->textField($model,'userId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'userId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'checkedInRestaurantId'); ?>
		<?php echo $form->textField($model,'checkedInRestaurantId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'checkedInRestaurantId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tip'); ?>
		<?php echo $form->textField($model,'tip',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'tip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sendPushNotification'); ?>
		<?php echo $form->textField($model,'sendPushNotification'); ?>
		<?php echo $form->error($model,'sendPushNotification'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shareOnFacebook'); ?>
		<?php echo $form->textField($model,'shareOnFacebook'); ?>
		<?php echo $form->error($model,'shareOnFacebook'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shareOnTwitter'); ?>
		<?php echo $form->textField($model,'shareOnTwitter'); ?>
		<?php echo $form->error($model,'shareOnTwitter'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shareOnInstagram'); ?>
		<?php echo $form->textField($model,'shareOnInstagram'); ?>
		<?php echo $form->error($model,'shareOnInstagram'); ?>
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