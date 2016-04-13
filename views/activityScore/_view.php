<?php
/* @var $this ActivityScoreController */
/* @var $data ActivityScore */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('facebookId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->facebookId), array('view', 'id'=>$data->facebookId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avilablePoints')); ?>:</b>
	<?php echo CHtml::encode($data->avilablePoints); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('totalPoints')); ?>:</b>
	<?php echo CHtml::encode($data->totalPoints); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score')); ?>:</b>
	<?php echo CHtml::encode($data->score); ?>
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

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('updateId')); ?>:</b>
	<?php echo CHtml::encode($data->updateId); ?>
	<br />

	*/ ?>

</div>