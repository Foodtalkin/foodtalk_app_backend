<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>

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
		<label>Dish Name</label>
		<input size="60" maxlength="32" name="dishName" id="dishName" type="text" required>
	</div>
	<div class="row">
		<label>Rating</label>
		<select id="rating" name="rating">
<!-- 			<option value=".5">0.5</option> -->
			<option value="1">1</option>
<!-- 			<option value="1.5">1.5</option> -->
			<option value="2">2</option>
<!-- 			<option value="2.5">2.5</option> -->
			<option selected value="3">3</option>
<!-- 			<option value="3.5">3.5</option> -->
			<option value="4">4</option>
<!-- 			<option value="4.5">4.5</option> -->
			<option value="5">5</option>
		</select>
	</div>
	
	<div class="row">
	
		<?php 
		echo $form->labelEx($model,'userId'); ?>
		<?php echo $form->dropDownList($model, 'userId', CHtml::listData(Manager::model()->findAll(), 'id', 'managerName')); ?>
		<?php echo $form->error($model,'userId'); ?>
	
		<?php // echo $form->textField($model,'userId',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'checkedInRestaurantId'); ?>
		<?php echo $form->dropDownList(
				$model, 
				'checkedInRestaurantId', 
				array()
			
				); ?>
		<?php // echo $form->textField($model,'checkedInRestaurantId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'checkedInRestaurantId'); ?>
	</div>
<script>
$( document ).ready(function() {
	$('#Post_checkedInRestaurantId').selectize({
	    valueField: 'id',
	    labelField: 'restaurantName',
	    searchField: 'restaurantName',
	    create: false,
	    render: {
	        option: function(item, escape) {

	            return '<div>' +
	                '<span class="title">' +
	                    '<span class="name">' + escape(item.restaurantName) + '</span>' +
	                '</span>' +
	                    '<br><span class="by">' + escape(item.address) +', ('+ escape(item.region) +')</span>' +
	            '</div>';
	        }
	    },
	    load: function(query, callback) {
	        if (!query.length) return callback();
	        $.ajax({
	        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/restaurant/list?sessionId=GUEST&searchText=' + encodeURIComponent(query),
	            type: 'GET',
	            error: function() {
	                callback();
	            },
	            success: function(res) {
	                callback(res.restaurants);
	            }
	        });
	    }
	});
	});

// 	$('#Post_checkedInRestaurantId').selectize({
// 		allowEmptyOption: true
// 	});
	$('#Post_userId').selectize({
		allowEmptyOption: false
	});
	$('.selectize-control').width('300px');
	$('#Post_checkedInRestaurantId').width('300px');
</script>	
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
        $('#Post_image').awesomeCropper(
        { width: 460, height: 460, debug: false }
        );
    });

    $( document ).ready(function() {
        $('.modal').hide();
        console.log( "ready!" );
    });
    
    </script> 
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->hiddenField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Review'); ?>
		<?php echo $form->textArea($model,'tip',array('size'=>80,'maxlength'=>200, 'style'=>'width: 600px;')); ?>
		<?php echo $form->error($model,'tip'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'sendPushNotification'); ?>
		<?php echo $form->hiddenField($model,'sendPushNotification'); ?>
		<?php echo $form->error($model,'sendPushNotification'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'shareOnFacebook'); ?>
		<?php echo $form->hiddenField($model,'shareOnFacebook'); ?>
		<?php echo $form->error($model,'shareOnFacebook'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'shareOnTwitter'); ?>
		<?php echo $form->hiddenField($model,'shareOnTwitter'); ?>
		<?php echo $form->error($model,'shareOnTwitter'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'shareOnInstagram'); ?>
		<?php echo $form->hiddenField($model,'shareOnInstagram'); ?>
		<?php echo $form->error($model,'shareOnInstagram'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isDisabled'); ?>
		<?php echo $form->checkBox($model,'isDisabled'); ?>
		<?php echo $form->error($model,'isDisabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'disableReason'); ?>
		<?php echo $form->textField($model,'disableReason',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'disableReason'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'createDate'); ?>
		<?php //echo $form->textField($model,'createDate'); ?>
		<?php //echo $form->error($model,'createDate'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'updateDate'); ?>
		<?php //echo $form->textField($model,'updateDate'); ?>
		<?php //echo $form->error($model,'updateDate'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'createId'); ?>
		<?php //echo $form->textField($model,'createId',array('size'=>10,'maxlength'=>10)); ?>
		<?php //echo $form->error($model,'createId'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'updateId'); ?>
		<?php //echo $form->textField($model,'updateId',array('size'=>10,'maxlength'=>10)); ?>
		<?php //echo $form->error($model,'updateId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Post' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->