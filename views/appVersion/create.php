<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs=array(
	'App Versions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AppVersion', 'url'=>array('index')),
	array('label'=>'Manage AppVersion', 'url'=>array('admin')),
);
?>

<h1>Create AppVersion</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>