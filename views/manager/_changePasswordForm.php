<?php
/* @var $this ManagerController */
/* @var $model Manager */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'manager-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) 
{
    echo '<div class="flash-' . $key . '" style="color: green; font-weight: bold; font-size: large;">' . $message . "</div>\n";
}

Yii::app()->clientScript->registerScript(
    'myHideEffect',
    '$(".flash-success").animate({opacity: 1.0}, 1000).fadeOut("slow");',
    CClientScript::POS_READY
);
?>
    
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'oldPassword'); ?>
		<?php echo $form->passwordField($model,'oldPassword',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'oldPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'newPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'confirmPassword'); ?>
		<?php echo $form->passwordField($model,'confirmPassword',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'confirmPassword'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->