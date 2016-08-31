<?php
/* @var $this FlagController */
/* @var $model Flag */

$this->breadcrumbs=array(
	'Region'=>array('admin'),
	'Create',
);

$this->menu=array(
// 	array('label'=>'List Flag', 'url'=>array('index')),
	array('label'=>'Manage', 'url'=>array('admin')),
);
?>

<h1>Create Region</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>