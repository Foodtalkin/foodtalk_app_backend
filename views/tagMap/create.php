<?php
/* @var $this TagMapController */
/* @var $model TagMap */

$this->breadcrumbs=array(
	'Tag Maps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TagMap', 'url'=>array('index')),
	array('label'=>'Manage TagMap', 'url'=>array('admin')),
);
?>

<h1>Create TagMap</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>