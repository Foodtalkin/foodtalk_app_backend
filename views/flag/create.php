<?php
/* @var $this FlagController */
/* @var $model Flag */

$this->breadcrumbs=array(
	'Flags'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Flag', 'url'=>array('index')),
	array('label'=>'Manage Flag', 'url'=>array('admin')),
);
?>

<h1>Create Flag</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>