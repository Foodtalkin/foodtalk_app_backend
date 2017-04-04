<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />

<?php
/* @var $this CuisineController */
/* @var $model Cuisine */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cuisine-form',
	'action'=>Yii::app()->getBaseUrl().'/index.php/cuisine/create',	
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'cuisineName'); ?>
		<?php //echo $form->textField($model,'cuisineName',array('size'=>60,'maxlength'=>255)); ?>
		<?php //echo $form->error($model,'cuisineName'); ?>
	</div>
        
<div>
<?php 
echo CHtml::dropDownList(
		'cuisine',
		'',
		CHtml::listData(
				Cuisine::model()->findAll(
						array("condition"=>"isDisabled = 0",'order'=>'cuisineName', "select"=>"id, cuisineName")),
				'id',
				'cuisineName'),
		array('empty'=>'Select a Cuisine','prompt'=> 'Please Select')
);
?>
<a target="_blank" href="#"   id="OpenCuisine" style="display: none;" >Open Cuisine</a>
</div>
<script>
$( document ).ready(function() {
	$('#cuisine').change(function (){
		$('#cuisine').prop("checked", true);
		$('#OpenCuisine').prop("href", $("#cuisine").val());
		$('#OpenCuisine').show();
	});
});


	$('#cuisine').selectize({
// 		plugins: ['remove_button'],
		allowEmptyOption: true,
		 maxItems: 1
	});

	$('.selectize-control').width('360px');
	
</script>

        <?php if($model->image) echo CHtml::image(Yii::app()->baseUrl . '/images/cuisine/thumb/' . $model->image) . '<br/>'; ?>
        
	<div class="row">
		<?php 
// 			echo $form->labelEx($model,'image'); 
// 			echo $form->fileField($model,'image');  
// 			echo $form->error($model,'image'); 
			?>
	</div>

	<div class="row buttons pull-left">
		<?php  echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->