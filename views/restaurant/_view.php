<?php
/* @var $this RestaurantController */
/* @var $data Restaurant */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('restaurantName')); ?>:</b>
	<?php echo CHtml::encode($data->restaurantName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contactName')); ?>:</b>
	<?php echo CHtml::encode($data->contactName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country')); ?>:</b>
	<?php echo CHtml::encode($data->country); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::encode($data->state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postcode')); ?>:</b>
	<?php echo CHtml::encode($data->postcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('latitude')); ?>:</b>
	<?php echo CHtml::encode($data->latitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('longitude')); ?>:</b>
	<?php echo CHtml::encode($data->longitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone1')); ?>:</b>
	<?php echo CHtml::encode($data->phone1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone2')); ?>:</b>
	<?php echo CHtml::encode($data->phone2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('highlights')); ?>:</b>
	<?php echo CHtml::encode($data->highlights); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('priceRange')); ?>:</b>
	<?php echo CHtml::encode($data->priceRange); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timing')); ?>:</b>
	<?php echo CHtml::encode($data->timing); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
	<?php echo CHtml::encode($data->image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facebookId')); ?>:</b>
	<?php echo CHtml::encode($data->facebookId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('twitterId')); ?>:</b>
	<?php echo CHtml::encode($data->twitterId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('googleId')); ?>:</b>
	<?php echo CHtml::encode($data->googleId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('linkedinId')); ?>:</b>
	<?php echo CHtml::encode($data->linkedinId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facebookLink')); ?>:</b>
	<?php echo CHtml::encode($data->facebookLink); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('twitterLink')); ?>:</b>
	<?php echo CHtml::encode($data->twitterLink); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('googleLink')); ?>:</b>
	<?php echo CHtml::encode($data->googleLink); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('linkedinLink')); ?>:</b>
	<?php echo CHtml::encode($data->linkedinLink); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('webAddress')); ?>:</b>
	<?php echo CHtml::encode($data->webAddress); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activationCode')); ?>:</b>
	<?php echo CHtml::encode($data->activationCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isActivated')); ?>:</b>
	<?php echo CHtml::encode($data->isActivated); ?>
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