<?php
/* @var $this AdwordsController */
/* @var $data Adwords */
$this->breadcrumbs = array (
		
		'links' => array (
				'Adwords' => 'admin',
				'create' 
		) 
);
?>


<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('entityId')); ?>:</b>
	<?php echo CHtml::encode($data->entityId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
	<?php echo CHtml::encode($data->image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('points')); ?>:</b>
	<?php echo CHtml::encode($data->points); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('couponCode')); ?>:</b>
	<?php echo CHtml::encode($data->couponCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paymentUrl')); ?>:</b>
	<?php echo CHtml::encode($data->paymentUrl); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('totalSlots')); ?>:</b>
	<?php echo CHtml::encode($data->totalSlots); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bookedSlots')); ?>:</b>
	<?php echo CHtml::encode($data->bookedSlots); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description2')); ?>:</b>
	<?php echo CHtml::encode($data->description2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expiry')); ?>:</b>
	<?php echo CHtml::encode($data->expiry); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('createId')); ?>:</b>
	<?php echo CHtml::encode($data->createId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updateId')); ?>:</b>
	<?php echo CHtml::encode($data->updateId); ?>
	<br />

	*/ ?>

</div>