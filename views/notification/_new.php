<script src="/themes/abound/js/selectize.js"></script>
<script src="/foodtalk/themes/abound/js/selectize.js"></script>
<h3>Create New Notification</h3>
<?php
/* @var $this NotificationController */
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
	
	<div class="row">
		<?php echo $form->labelEx($model,'Target Screen *'); ?>
		<?php echo $form->dropDownList($model, 'eventType', CHtml::listData(
				EventClass::model()->findAll(
						array("condition"=>"eventGroup = 3 and isDisabled = 0 ", "select"=>"id, title" )
						)
				, 'id', 'title'
				),  array('empty'=>'Select a Target Screen','required'=>true)); ?>
		<?php echo $form->error($model,'eventType'); ?>
	</div>
<div>
<?php 
echo CHtml::dropDownList(
		'Event[elementId]',
		'',
		CHtml::listData(
				Restaurant::model()->findAll(
						array("condition"=>"isActivated = 1 and isDisabled = 0 ",'order'=>'restaurantName', "select"=>"id, CONCAT(restaurantName, ' (', IFNULL(area, IFNULL(address, '') )  , ')', IF(isDisabled = 1, '-DISABLE RESTAURANT', IF(isActivated = 0, '-INACTIVE RESTAURANT', ''))) as restaurantName")
						),
				'id',
				'restaurantName'
		),
		array(
				'empty'=>array('Select a Restaurant'),
				'style'=>'display:none'
				
)
		);
?>
</div>
	<div class="row">
		<?php
// 		$model->region = isset($_SESSION['region'])? $_SESSION['region'] : '';
		echo $form->labelEx($model,'region'); ?>
		<?php echo $form->dropDownList($model, 'region', CHtml::listData(Region::model()->findAll(), 'name', 'name'), array('empty'=>'Select a Region'));
		echo $form->error($model,'region'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Notify Message *'); ?>
		<?php echo $form->textArea($model,'message',array('required'=>true, 'size'=>80,'maxlength'=>200, 'style'=>'width: 400px;')); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Notify Channel *'); ?>
		<?php echo $form->radioButtonList($model,'channel',array( 'Tester'=>'Tester', 'FoodTalk'=>'FoodTalk')); ?>
		<?php echo $form->error($model,'channel'); ?>
	</div>

	<div id="datetimepicker2" class="row input-append date">

		<?php echo $form->labelEx($model,'Notify Time *'); ?>
		<?php echo $form->textField($model,'eventDate', array('value'=>date("Y-m-d H:i:s") ,'required'=>true, 'readonly'=>true)); ?>
		<?php echo $form->error($model,'eventDate'); ?>
	<span class="add-on">
    	  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
     	 </i>
    </span>
		
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Post' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">


function validateForm(form){
	
}


	$(document).ready(function(){
		
		$("#post-form").submit(function(event){

// 			alert($('#Event_elementId').val());
		    if($("#Event_eventType").val() == 53){
				if($('#Event_elementId').val() < 1 ){
					event.preventDefault();
					alert('please select a resturant from the list');
				}
		    }
			return true;  

		});
		
		$('#Event_elementId').selectize({
			allowEmptyOption: true
		});
		
		$('.selectize-control').hide();
		
	    $("#Event_eventType").change(function(){

		    if($("#Event_eventType").val() == 53){
				$('.selectize-control').show();
		    }else{
				$('.selectize-control').hide();
		    }
		        
	    });
	});

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


