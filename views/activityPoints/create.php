<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

$this->breadcrumbs=array(
	'Activity Points'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ActivityPoints', 'url'=>array('index')),
	array('label'=>'Manage ActivityPoints', 'url'=>array('admin')),
);
?>

<h1>Create ActivityPoints</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>