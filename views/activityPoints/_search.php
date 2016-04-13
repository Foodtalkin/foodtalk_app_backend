<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activityTable'); ?>
		<?php echo $form->textField($model,'activityTable',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activityTitle'); ?>
		<?php echo $form->textField($model,'activityTitle',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'platform'); ?>
		<?php echo $form->textField($model,'platform',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activityDesc'); ?>
		<?php echo $form->textArea($model,'activityDesc',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'points'); ?>
		<?php echo $form->textField($model,'points'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'timefactor'); ?>
		<?php echo $form->textField($model,'timefactor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minimum'); ?>
		<?php echo $form->textField($model,'minimum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maximum'); ?>
		<?php echo $form->textField($model,'maximum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'penality'); ?>
		<?php echo $form->textField($model,'penality'); ?>
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