<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />

<link rel="stylesheet" type="text/css"
	href="http://antenna.io/demo/jquery-bar-rating/dist/themes/css-stars.css" />
<script
	src="http://antenna.io/demo/jquery-bar-rating/jquery.barrating.js"></script>


<div class="form">

<?php 
	$form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'post-form',
			'type' => 'horizontal',
				
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); 
	echo $form->errorSummary($model); ?>


	<div class="form-group">
		<label class="col-sm-3 control-label" for="Post_tip">Dish Name</label>
		<div class="col-sm-3 col-sm-9">
			<input class="form-control" placeholder="Dish Name" name="dishName"
				id="dishName" type="text" maxlength="500">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label" for="Post_tip">Rating</label>
		<div class="col-sm-3 col-sm-9">

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
	</div>

	<?php 
	echo $form->dropDownListGroup($model, 'userId', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(Manager::model()->findAll(), 'id', 'managerName'),
					'htmlOptions' => array(),
				)
	));
	
	echo $form->dropDownListGroup($model, 'checkedInRestaurantId', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
// 				'widgetOptions' => array(
// 					'data' => CHtml::listData(Manager::model()->findAll(), 'id', 'managerName'),
// 					'htmlOptions' => array(),
// 				)
	)); 
	?>
	
		<div class="form-group">
		<label class="col-sm-3 control-label" for="Post_tip">Image</label>
		<div class="col-sm-3 col-sm-9">
			<?php echo $form->hiddenField($model,'image'); ?>
			</div>
	</div>

	<?php
	echo $form->textAreaGroup ( $model, 'tip', array (
			'wrapperHtmlOptions' => array (
					'class' => 'col-sm-3',
			),
			'widgetOptions' => array (
					'htmlOptions' => array (
							'rows' => 3 
					),
					)
			
				)
			); 
	
	
	echo $form->switchGroup($model, 'isDisabled',
			array(
				'widgetOptions' => array(
						'options' => array(
														'onColor'=> 'warning', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
														'onText'=> 'YES',
														'offText' => 'NO', 
														'offColor'=> 'success',
												),
				)
			)
		); 
	
	echo $form->textFieldGroup($model,'disableReason',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>

	<div class="form-group">
		<label class="col-sm-3 control-label" for="Post_tip"></label>
		<div class="col-sm-3 col-sm-9">
				<?php
				
				$this->widget(
						'booster.widgets.TbButton',
						array(
								'context' => 'primary',
								'label' => $model->isNewRecord ? 'Post' : 'Save',
								//                 'url' => '#',
								'htmlOptions' => array('type'=>'Submit', "value"=>"Submit"),
						));
				
				?>
			</div>
	</div>

<?php $this->endWidget(); ?>

</div>
<!-- form -->


<style>
.modal-body canvas {
	display: none;
}
</style>
<script>
$( document ).ready(function() {
	  $('#rating').barrating({
          theme: 'css-stars',
          showSelectedRating: false
      });
      
	  $( ".fadeout" ).fadeOut( "slow", function() {
	  });
});

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
// 	$('.selectize-control').width('300px');
// 	$('#Post_checkedInRestaurantId').width('300px');

	
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

<link
	href="http://www.jqueryscript.net/demo/Simple-jQuery-Client-Side-Image-Cropping-Plugin-Awesome-Cropper/components/imgareaselect/css/imgareaselect-default.css"
	rel="stylesheet" media="screen">
<script
	src="http://www.jqueryscript.net/demo/Simple-jQuery-Client-Side-Image-Cropping-Plugin-Awesome-Cropper/components/imgareaselect/scripts/jquery.imgareaselect.js"></script>
<script
	src="http://www.jqueryscript.net/demo/Simple-jQuery-Client-Side-Image-Cropping-Plugin-Awesome-Cropper/build/jquery.awesome-cropper.js"></script>
<input id="sample_s_input" type="hidden" name="img">