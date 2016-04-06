<h2>Notification Id: <?php echo $model->id; ?></h2>

<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));


// print_r($model);


$disabled = false;
if(  strtotime($model->eventDate) < time() )
$disabled = true;
?>



<table class="general" id="yw0"><tbody>
<tr class="even">

<th><?php echo $form->labelEx($model,'Target Screen'); ?></th><td>
<?php echo $form->dropDownList($model, 'eventType', CHtml::listData(
						EventClass::model()->findAll(
							array("condition"=>"eventGroup = 3 and isDisabled = 0 ", "select"=>"id, title" )
						)
						, 'id', 'title')
					, array('empty'=>'Select a Target Screen','required'=>true, 'disabled'=>$disabled )); ?>
<?php echo $form->error($model,'eventType'); ?>
</td></tr>

<tr class="odd"><th><?php echo $form->labelEx($model,'Notify Message'); ?></th><td>
<?php echo $form->textArea($model,'message',array('required'=>true, 'size'=>80,'maxlength'=>200, 'style'=>'width: 400px;','disabled'=>$disabled  )); ?>
<?php echo $form->error($model,'message'); ?>
</td></tr>
<th><?php echo $form->labelEx($model,'Notify Channel *'); ?></th><td>
		
		<?php echo $form->radioButtonList($model,'channel',array( 'Tester'=>'Tester', 'FoodTalk'=>'FoodTalk'), array('required'=>true, 'disabled'=>$disabled  )); ?>
		<?php echo $form->error($model,'channel'); ?>
</td></tr>

<tr class="odd"><th>

<?php echo $form->labelEx($model,'Notify Time *'); ?>
</th><td id="datetimepicker2" class="input-append date">
		<?php echo $form->textField($model,'eventDate', array('required'=>true,'disabled'=>$disabled )); ?>
		<?php echo $form->error($model,'eventDate'); ?>
<span class="add-on">
    	  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
     	 </i>
    </span>
</td></tr>

<tr class="even"><th>
</th><td>
		<?php if(!$disabled) echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save'); ?>
</td></tr>




</tbody></table>
<input type="hidden" name="action" value="update">

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
