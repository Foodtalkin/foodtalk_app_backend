<?php
/* @var $this AdwordsController */
/* @var $model Adwords */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'adwords-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'entityId'); ?>
		<?php echo $form->textField($model,'entityId'); ?>
		<?php echo $form->error($model,'entityId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>



<style>
.modal-body canvas {
	display: none;
}
</style>
<link href="http://www.jqueryscript.net/demo/Simple-jQuery-Client-Side-Image-Cropping-Plugin-Awesome-Cropper/components/imgareaselect/css/imgareaselect-default.css" rel="stylesheet" media="screen">
<script src="http://www.jqueryscript.net/demo/Simple-jQuery-Client-Side-Image-Cropping-Plugin-Awesome-Cropper/components/imgareaselect/scripts/jquery.imgareaselect.js"></script> 
<script src="http://www.jqueryscript.net/demo/Simple-jQuery-Client-Side-Image-Cropping-Plugin-Awesome-Cropper/build/jquery.awesome-cropper.js"></script> 
	 <input id="sample_s_input" type="hidden" name="img" >

<script>
    $(document).ready(function () {
        $('#Adwords_image').awesomeCropper(
        { width: 460, height: 460, debug: false }
        );
    });

    $( document ).ready(function() {
        $('.modal').hide();
        console.log( "ready!" );
    });
    
    </script> 
	<div class="row">
	
		<img alt="AddImage" src="<?php echo imagePath('post').$model->image ?> ">
		
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->hiddenField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'points'); ?>
		<?php echo $form->textField($model,'points'); ?>
		<?php echo $form->error($model,'points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'paymentUrl'); ?>
		<?php echo $form->textField($model,'paymentUrl',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'paymentUrl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'totalSlots'); ?>
		<?php echo $form->textField($model,'totalSlots'); ?>
		<?php echo $form->error($model,'totalSlots'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description2'); ?>
		<?php echo $form->textArea($model,'description2',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description2'); ?>
	</div>

	<div id="datetimepicker2" class="row input-append date">

		<?php echo $form->labelEx($model,'expiry'); ?>
		<?php echo $form->textField($model,'expiry', array('value'=>date("Y-m-d H:i:s") ,'required'=>true, 'readonly'=>true)); ?>
		<?php echo $form->error($model,'expiry'); ?>
	<span class="add-on">
    	  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
     	 </i>
    </span>
		
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		
				<?php echo $form->dropDownList($model, 'type', CHtml::listData(
						
				array(array('id'=>'event', 'title'=>'Event'))
						, 'id', 'title'
						
				),  array('required'=>true)); ?>
		
		<?php // echo $form->textField($model,'type'); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
$(function() {
    $('#datetimepicker2').datetimepicker({
      language: 'en',
      pickSeconds: false,
      pick12HourFormat: true,
      startDate: '2015-11-11',
      format: 'yyyy-MM-dd hh:mm:ss',
    });
  });
</script>

</div><!-- form -->
