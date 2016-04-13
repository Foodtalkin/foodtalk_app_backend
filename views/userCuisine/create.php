<?php
/* @var $this UserCuisineController */
/* @var $model UserCuisine */

$this->breadcrumbs=array(
	'User Cuisines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserCuisine', 'url'=>array('index')),
	array('label'=>'Manage UserCuisine', 'url'=>array('admin')),
);
?>

<h1>Create UserCuisine</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>