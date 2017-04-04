<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->breadcrumbs = array (
		'links' => array (
				'Restaurant'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
	array('label'=>'Manage Restaurant', 'url'=>'admin'),
	array('label'=>'Create Restaurant', 'url'=>'create'),
	array('label'=>'update Restaurant', 'url'=>'update/'.$model->id,'itemOptions' => array ('class' => 'active') ),
	array('label'=>'View Restaurant', 'url'=>'view/'.$model->id),
);

$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'update Restaurant : '.$model->restaurantName,
				'fixed' => false,
				'fluid' => true,
		)
);

$this->renderPartial('_form', array('model'=>$model)); ?>