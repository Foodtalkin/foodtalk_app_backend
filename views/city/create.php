<?php
/* @var $this CityController */
/* @var $model City */

$this->breadcrumbs=array(
	'Cities'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

<h1>Create City</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>