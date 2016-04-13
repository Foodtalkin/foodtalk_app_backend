<?php
/* @var $this ActivityPointsController */
/* @var $data ActivityPoints */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activityTable')); ?>:</b>
	<?php echo CHtml::encode($data->activityTable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activityTitle')); ?>:</b>
	<?php echo CHtml::encode($data->activityTitle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('platform')); ?>:</b>
	<?php echo CHtml::encode($data->platform); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activityDesc')); ?>:</b>
	<?php echo CHtml::encode($data->activityDesc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('points')); ?>:</b>
	<?php echo CHtml::encode($data->points); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timefactor')); ?>:</b>
	<?php echo CHtml::encode($data->timefactor); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('minimum')); ?>:</b>
	<?php echo CHtml::encode($data->minimum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maximum')); ?>:</b>
	<?php echo CHtml::encode($data->maximum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('penality')); ?>:</b>
	<?php echo CHtml::encode($data->penality); ?>
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