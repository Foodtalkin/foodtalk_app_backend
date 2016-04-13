<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'role'); ?>
		<?php echo $form->textField($model,'role',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'restaurantName'); ?>
		<?php echo $form->textField($model,'restaurantName',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contactName'); ?>
		<?php echo $form->textField($model,'contactName',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postcode'); ?>
		<?php echo $form->textField($model,'postcode',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone1'); ?>
		<?php echo $form->textField($model,'phone1',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone2'); ?>
		<?php echo $form->textField($model,'phone2',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'highlights'); ?>
		<?php echo $form->textField($model,'highlights',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'priceRange'); ?>
		<?php echo $form->textField($model,'priceRange',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'timing'); ?>
		<?php echo $form->textField($model,'timing',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'facebookId'); ?>
		<?php echo $form->textField($model,'facebookId',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'twitterId'); ?>
		<?php echo $form->textField($model,'twitterId',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'googleId'); ?>
		<?php echo $form->textField($model,'googleId',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'linkedinId'); ?>
		<?php echo $form->textField($model,'linkedinId',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'facebookLink'); ?>
		<?php echo $form->textField($model,'facebookLink',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'twitterLink'); ?>
		<?php echo $form->textField($model,'twitterLink',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'googleLink'); ?>
		<?php echo $form->textField($model,'googleLink',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'linkedinLink'); ?>
		<?php echo $form->textField($model,'linkedinLink',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'webAddress'); ?>
		<?php echo $form->textField($model,'webAddress',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activationCode'); ?>
		<?php echo $form->textField($model,'activationCode',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isActivated'); ?>
		<?php echo $form->textField($model,'isActivated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isDisabled'); ?>
		<?php echo $form->textField($model,'isDisabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disableReason'); ?>
		<?php echo $form->textField($model,'disableReason',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createDate'); ?>
		<?php echo $form->textField($model,'createDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updateDate'); ?>
		<?php echo $form->textField($model,'updateDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createId'); ?>
		<?php echo $form->textField($model,'createId',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updateId'); ?>
		<?php echo $form->textField($model,'updateId',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->