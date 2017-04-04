<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

$form=$this->beginWidget('booster.widgets.TbActiveForm', array(
		'type' => 'horizontal',
// $form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-points-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="form-group">
		<label class="col-sm-3 control-label required"
			for="ActivityPoints_activityTable">Fields with <span class="required">*</span>
			are required.
		</label>
		<div class="col-sm-3 col-sm-9"></div>
	</div>

		<?php
		
		echo $form->textFieldGroup($model, 'activityTable',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',)));
		echo $form->textFieldGroup($model, 'activityTitle',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',)));
		echo $form->textFieldGroup($model, 'platform',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',)));
		echo $form->textAreaGroup($model, 'activityDesc',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',)));
		echo $form->textFieldGroup($model, 'points',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',)));
		echo $form->textFieldGroup($model, 'timefactor',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',),'labelOptions' => array('label' => 'Timefactor In Minutes',) ));
		echo $form->textFieldGroup($model, 'minimum',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',),'labelOptions' => array('label' => 'Minimum Points',) ));
		echo $form->textFieldGroup($model, 'maximum',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',),'labelOptions' => array('label' => 'Maximum Points',)));
		echo $form->textFieldGroup($model, 'penality',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',),'labelOptions' => array('label' => 'Points For Penality',)));
		
		?>

<div class="form-group">
		<label class="col-sm-3 control-label required"
			for="ActivityPoints_maximum"></label>
		<div class="col-sm-3 col-sm-9">
				<?php
				$this->widget(
						'booster.widgets.TbButton',
						array(
								'context' => 'primary',
								'label' => $model->isNewRecord ? 'Create' : 'Save',
								//                 'url' => '#',
								'htmlOptions' => array('type'=>'Submit', "value"=>"Submit"),
						));
				
				?>
				
		</div>
	</div>

<?php $this->endWidget(); ?>
</div>
<br>
<!-- form -->