<?php
/* @var $this AdwordsController */
/* @var $model Adwords */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'adwords-form',
		'type' => 'horizontal',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


		<?php echo $form->errorSummary($model); ?>
		<?php echo $form->dropDownListGroup($model, 'adType', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(AdType::model()->findAll('isDisabled = 0'), 'id', 'name'),
					'htmlOptions' => array(),
				)
			)); ?>
		<?php echo $form->textFieldGroup($model, 'entityId',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textFieldGroup($model,'title',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textAreaGroup($model,'description',array('wrapperHtmlOptions' => array('class' => 'col-sm-3'), 'widgetOptions' => array('htmlOptions' => array('rows' => 3),))); ?>
		<?php echo $form->datePickerGroup($model,'expiry',
				array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>		
	<div class="form-actions">
		<?php $this->widget(
			'booster.widgets.TbButton',
			array(
				'buttonType' => 'submit',
				'context' => 'primary',
				'label' => 'Submit'
			)
		); ?>
		<?php $this->widget(
			'booster.widgets.TbButton',
			array('buttonType' => 'reset', 'label' => 'Reset')
		); ?>
	</div>
</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>


</div><!-- form -->
