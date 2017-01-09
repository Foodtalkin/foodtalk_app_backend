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
<select style="display:none" id="Event_elementId">
</select>

<select style="display:none" id="offer_elementId">
</select>

<select style="display:none" id="purchase_elementId">
</select>

<?php 
// echo CHtml::dropDownList(
// 		'Event[elementId]',
// 		'',
// 		CHtml::listData(
// 				Restaurant::model()->findAll(
// 						array("condition"=>"isActivated = 1 and isDisabled = 0 and suggested = 1 ",'order'=>'restaurantName', "select"=>"id, CONCAT(restaurantName, ' (', IFNULL(area, IFNULL(address, '') )  , ')', IF(isDisabled = 1, '-DISABLE RESTAURANT', IF(isActivated = 0, '-INACTIVE RESTAURANT', ''))) as restaurantName")
// 						),
// 				'id',
// 				'restaurantName'
// 		),
// 		array(
// 				'empty'=>array('Select a Restaurant'),
// 				'style'=>'display:none'
				
// )
// 		);
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


$( document ).ready(function() {
	$('#Event_elementId').selectize({
		allowEmptyOption: true,
	    valueField: 'id',
	    labelField: 'restaurantName',
	    searchField: 'restaurantName',
	    wrapperClass: 'restaurant-control',
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

$( document ).ready(function() {
	$('#offer_elementId').selectize({
		allowEmptyOption: true,
	    valueField: 'id',
	    labelField: 'title',
	    searchField: 'title',
	    wrapperClass: 'offer-control',
	    create: false,
	    render: {
	        option: function(item, escape) {

	            return '<div><span class="title"><span class="name">' + escape(item.title) + '</span></span><br><span class="by">' + escape(item.shortDescription) +', ('+ escape(item.cityText) +')</span></div>';
	        }
	    },
	    load: function(query, callback) {
	        if (!query.length) return callback();
	        $.ajax({
	        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/storeOffer/list?sessionId=GUEST&searchText=' + encodeURIComponent(query),
	            type: 'GET',
	            error: function() {
	                callback();
	            },
	            success: function(res) {
	                callback(res.storeOffers);
	            }
	        });
	    }
	});
	});


$( document ).ready(function() {
	$('#purchase_elementId').selectize({
		allowEmptyOption: true,
	    valueField: 'storeItemId',
	    labelField: 'title',
	    searchField: 'title',
	    wrapperClass: 'purchase-control',
	    create: false,
	    render: {
	        option: function(item, escape) {

	            return '<div><span class="title"><span class="name">' + escape(item.title) + '</span></span><br><span class="by">' + escape(item.shortDescription) +', ('+ escape(item.cityText) +')</span></div>';
	        }
	    },
	    load: function(query, callback) {
	        if (!query.length) return callback();
	        $.ajax({
	        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/storeOffer/list?sessionId=GUEST&searchText=' + encodeURIComponent(query),
	            type: 'GET',
	            error: function() {
	                callback();
	            },
	            success: function(res) {
	                callback(res.storeOffers);
	            }
	        });
	    }
	});
	});


	var elementId = '';

	$(document).ready(function(){
		
		$("#post-form").submit(function(event){

			$( '#' + elementId ).attr('name', 'Event[elementId]');
			
		    if( $("#Event_eventType").val() == 53 || $("#Event_eventType").val() == 57 ){
				if($( '#' + elementId ).val() < 1 ){
					event.preventDefault();
					alert('please select an option from the list');
				}
		    }
			return true;  

		});
		
		
		
		$('.restaurant-control').hide();
		$('.offer-control').hide();
		$('.purchase-control').hide();
		
	    $("#Event_eventType").change(function(){

		    if($("#Event_eventType").val() == 53 || $("#Event_eventType").val() == 57 || $("#Event_eventType").val() == 58){

		    	$('.restaurant-control').hide();
				$('.offer-control').hide();
				$('.purchase-control').hide();
		    	
			    if($("#Event_eventType").val() == 53){
					$('.restaurant-control').show();
					elementId = 'Event_elementId';
			    }

			    if($("#Event_eventType").val() == 57){
					$('.offer-control').show();
					elementId = 'offer_elementId';
					
			    }

			    if($("#Event_eventType").val() == 58){
					$('.purchase-control').show();
					elementId = 'purchase_elementId';
			    }
				
		    }else{
		    	$('.restaurant-control').hide();
				$('.offer-control').hide();
				$('.purchase-control').hide();
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


