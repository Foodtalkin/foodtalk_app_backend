<?php
/* @var $this NotificationController */


$this->breadcrumbs=array(
		'links' => array('Notification'),
);


// $this->menu=array(
// 		array('label'=>'Create', 'url'=>'create'),
// 		array('label'=>'list', 'url'=>'admin'),
// // 		array('label'=>'Disabled Post', 'url'=>array('notification')),
// );

$action = Yii::app()->controller->action->id;

$this->menu=array(
		array('label' => 'Manage','url' => 'admin','itemOptions' => $action=='create'?array():array('class' => 'active' )),
		array('label' => 'Create New','url' => 'create','itemOptions' => $action=='create'?array('class' => 'active'):array())
		,
);

$eventTypes = Event::getEventsType();

?>
 <?php  $this->renderPartial($view, array('model'=>$model, 'eventTypes'=>$eventTypes)); ?>