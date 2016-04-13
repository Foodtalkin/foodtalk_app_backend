<?php
/* @var $this SubscriptionController */
/* @var $data Subscription */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userId')); ?>:</b>
	<?php echo CHtml::encode($data->userId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isDisabled')); ?>:</b>
	<?php echo CHtml::encode($data->isDisabled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disableReason')); ?>:</b>
	<?php echo CHtml::encode($data->disableReason); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createDate')); ?>:</b>
	<?php echo CHtml::encode($data->createDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updateDate')); ?>:</b>
	<?php echo CHtml::encode($data->updateDate); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('createId')); ?>:</b>
	<?php echo CHtml::encode($data->createId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updateId')); ?>:</b>
	<?php echo CHtml::encode($data->updateId); ?>
	<br />

	*/ ?>

</div>