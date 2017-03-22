<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'app-version-form',
		'type' => 'horizontal',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

		<?php echo $form->textFieldGroup($model, 'platform',array('wrapperHtmlOptions' => array('class' => 'col-sm-3'), 'widgetOptions' => array('htmlOptions' => array('disabled' => true)) ) ); ?>
		<?php echo $form->textFieldGroup($model, 'version',array('wrapperHtmlOptions' => array('class' => 'col-sm-3'), 'widgetOptions' => array('htmlOptions' => array('size'=>20,'maxlength'=>20)) ) ); ?>
		<?php echo $form->textFieldGroup($model, 'message',array('wrapperHtmlOptions' => array('class' => 'col-sm-3'), 'widgetOptions' => array('htmlOptions' => array('size'=>60,'maxlength'=>200)) ) ); ?>
		
				<?php
		echo $form->switchGroup($model, 'isCritical',
			array(
				'widgetOptions' => array(
					
						'options' => array(
														'labelText' => 'YES', //null, 'mini', 'small', 'normal', 'large
														'onText' => '&nbsp;', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
														'onColor'=> 'info',
														'onText'=> '&nbsp',
														'offText' => 'No',  // 'primary', 'info', 'success', 'warning', 'danger', 'default'
														'offColor'=> '',
												),
// 					'onText' => 'enabled', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
// 					'offText' => 'disabled',
				)
			)
		); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->