<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */

$this->breadcrumbs=array(
	'Activity Scores'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ActivityScore', 'url'=>array('index')),
	array('label'=>'Manage ActivityScore', 'url'=>array('admin')),
);
?>

<h1>Create ActivityScore</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>