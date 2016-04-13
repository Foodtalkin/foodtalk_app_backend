<?php
/* @var $this RestaurantCuisineController */
/* @var $model RestaurantCuisine */

$this->breadcrumbs=array(
	'Restaurant Cuisines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RestaurantCuisine', 'url'=>array('index')),
	array('label'=>'Manage RestaurantCuisine', 'url'=>array('admin')),
);
?>

<h1>Create RestaurantCuisine</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>