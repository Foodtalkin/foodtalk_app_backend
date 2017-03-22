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
		
		<?php echo $form->textFieldGroup($model,'actionButtonText',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		
		<?php echo $form->textAreaGroup($model,'description',array('wrapperHtmlOptions' => array('class' => 'col-sm-3'), 'widgetOptions' => array('htmlOptions' => array('rows' => 3),))); ?>
		
		<?php echo $form->datePickerGroup($model,'startDate',
				array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'startDate' => '-0d',
						'format'=>'yyyy-mm-dd'	
// 						'endDate'=> '+0d',
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>		
		<?php echo $form->datePickerGroup($model,'expiry',
				array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'startDate' => '-0d',
						'format'=>'yyyy-mm-dd'		
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>		
		
		<?php
		echo $form->switchGroup($model, 'isDisabled',
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
		
		<?php
		
// 		$this->widget(
// 				'booster.widgets.TbSwitch',
// 				array(
// 						'name' => 'isDisabled',
// 						'options' => array(
// 								'size' => 'large', //null, 'mini', 'small', 'normal', 'large
// 								'onText' => 'enabled', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
// 								'offText' => 'disabled',  // 'primary', 'info', 'success', 'warning', 'danger', 'default'
// 						),
// 				)
// 		);
		
		echo $form->textFieldGroup($model,'latitude',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textFieldGroup($model,'longitude',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		
				<?php echo $form->textFieldGroup($model,'priority',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
				<?php echo $form->textFieldGroup($model,'position',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>

		<div class="form-group">
		<div class="col-sm-3 col-sm-9">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
</div>

<!-- form -->
