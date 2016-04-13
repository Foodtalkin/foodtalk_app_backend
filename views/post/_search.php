<?php
/* @var $this PostController */
/* @var $model Post */
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
		<?php echo $form->label($model,'userId'); ?>
		<?php echo $form->textField($model,'userId',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'checkedInRestaurantId'); ?>
		<?php echo $form->textField($model,'checkedInRestaurantId',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tip'); ?>
		<?php echo $form->textField($model,'tip',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sendPushNotification'); ?>
		<?php echo $form->textField($model,'sendPushNotification'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shareOnFacebook'); ?>
		<?php echo $form->textField($model,'shareOnFacebook'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shareOnTwitter'); ?>
		<?php echo $form->textField($model,'shareOnTwitter'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shareOnInstagram'); ?>
		<?php echo $form->textField($model,'shareOnInstagram'); ?>
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