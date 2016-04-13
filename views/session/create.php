<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Session', 'url'=>array('index')),
	array('label'=>'Manage Session', 'url'=>array('admin')),
);
?>

<h1>Create Session</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>