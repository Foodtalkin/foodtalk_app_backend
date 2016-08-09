<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs=array(
	'Adwords'=>array('index'),
	'Create',
);

$this->menu=array(
// 	array('label'=>'List Adwords', 'url'=>array('index')),
	array('label'=>'Manage Adwords', 'url'=>array('admin')),
);
?>

<h1>Create Adwords</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>