<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />

<?php
/* @var $this NotificationController */
/* @var $model Post */
/* @var $form CActiveForm */

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Create New Notification',
		)
);
?>
<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'post-form',
	'type' => 'horizontal',
		
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

			<?php echo $form->dropDownListGroup($model, 'eventType', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(EventClass::model()->findAll(
						array("condition"=>"eventGroup = 3 and isDisabled = 0 ", "select"=>"id, title" )
						), 'id', 'title'),
					'htmlOptions' => array('empty'=>'Select a Target Screen','required'=>true ),
				),
				'labelOptions' => array(
							'label' => 'Target Screen :',
				)
			)); ?>
<div class="form-group"><label class="col-sm-3 control-label required" for="Event_eventType"></label><div class="col-sm-3 col-sm-9">
<select style="display:none" id="Event_elementId">
</select>

<select style="display:none" id="offer_elementId">
</select>

<select style="display:none" id="purchase_elementId">
</select>

<?php 
echo CHtml::dropDownList( '', '',
		CHtml::listData(
				News::model()->findAll(
						array( "condition"=>" isDisabled = 0 ", "select"=>"id, title", 'limit' => 25, 'order' => 'createDate DESC' )),
				'id',
				'title'),
		array(
				'empty'=>'Select a news',
				'id'=>'news_elementId',
				'style'=>'display:none',
				'class'=>"form-control"
				
)
);
?>
</div></div><div>

			<?php echo $form->dropDownListGroup($model, 'cityId', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(City::model()->findAll(
						array("condition"=>" isDisabled = 0 ", "select"=>"id, cityName" )
						), 'id', 'cityName'),
					'htmlOptions' => array('empty'=>'Select a City'),
				)
			)); ?>

			<?php echo $form->dropDownListGroup($model, 'regionId', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(Region::model()->findAll(
						array( "select"=>"id, name" )
						), 'id', 'name'),
					'htmlOptions' => array('empty'=>'Select a Region'),
				)
			)); ?>


<?php echo $form->textAreaGroup(
			$model,
			'message',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'htmlOptions' => array('rows' => 5,'required'=>true),
				),
// 				'htmlOptions' => array('required'=>true ),
				'labelOptions' => array(
						'label' => 'Notify Message *',
				)
			)
		); ?>

<?php echo $form->radioButtonListGroup(
			$model,
			'channel',
			array(
					'wrapperHtmlOptions' => array(
							'class' => 'col-sm-3',
					),
				'widgetOptions' => array(
					'data' => array(
						'Tester'=>'Tester channel', 'FoodTalk'=>'Food Talk channel'
					)
				),
				'inline'=>'checkbox-horizontal'	
			)
		); ?>
		
		<?php echo $form->dateTimePickerGroup($model,'eventDate',
				array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'startDate' => '-0d',
						'format'=>'yyyy-mm-dd H:i:s'	
// 						'endDate'=> '+0d',
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>

<div class="form-group"><label class="col-sm-3 control-label required" for="Event_eventType"></label><div class="col-sm-3 col-sm-9">
		<?php 
		
		$this->widget(
				'booster.widgets.TbButton',
				array(
						'context' => 'primary',
						'label' => $model->isNewRecord ? 'Post' : 'Save',
						//                 'url' => '#',
						'htmlOptions' => array('type'=>'Submit', "value"=>"Submit"),
				));
		
// 		echo CHtml::submitButton($model->isNewRecord ? 'Post' : 'Save'); ?>
	</div></div>

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
	    valueField: 'storeItemId',
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
		$('#news_elementId').hide();
		
		
		
	    $("#Event_eventType").change(function(){

		    if($("#Event_eventType").val() == 53 || $("#Event_eventType").val() == 57 || $("#Event_eventType").val() == 58 || $("#Event_eventType").val() == 59){

		    	$('.restaurant-control').hide();
				$('.offer-control').hide();
				$('.purchase-control').hide();
				$('#news_elementId').hide();

		    	
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
			    
			    if($("#Event_eventType").val() == 59){
					$('#news_elementId').show();
					elementId = 'news_elementId';
			    }

			    
				
		    }else{
		    	$('.restaurant-control').hide();
				$('.offer-control').hide();
				$('.purchase-control').hide();
				$('#news_elementId').hide();
		    }
		        
	    });
	});
	
	$( document ).ready(function() {
		$("#Event_eventDate").val("<?php echo date("Y-m-d H:i:s") ?>");
		$("#Event_eventDate").attr('readonly', true);
	});
</script>


