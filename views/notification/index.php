<?php
/* @var $this NotificationController */

$this->breadcrumbs=array(
	'Notification',
);

$this->menu=array(
		array('label'=>'Create', 'url'=>array('notification/create')),
		array('label'=>'list', 'url'=>array('notification/admin')),
// 		array('label'=>'Disabled Post', 'url'=>array('notification')),
);

$eventTypes = Event::getEventsType();

?>
 <?php  $this->renderPartial($view, array('model'=>$model, 'eventTypes'=>$eventTypes)); ?>