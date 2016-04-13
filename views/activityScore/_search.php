<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'facebookId'); ?>
		<?php echo $form->textField($model,'facebookId',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'avilablePoints'); ?>
		<?php echo $form->textField($model,'avilablePoints'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'totalPoints'); ?>
		<?php echo $form->textField($model,'totalPoints'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'score'); ?>
		<?php echo $form->textField($model,'score'); ?>
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