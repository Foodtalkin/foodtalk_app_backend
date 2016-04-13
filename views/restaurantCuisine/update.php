<?php
/* @var $this RestaurantCuisineController */
/* @var $model RestaurantCuisine */

$this->breadcrumbs=array(
	'Restaurant Cuisines'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RestaurantCuisine', 'url'=>array('index')),
	array('label'=>'Create RestaurantCuisine', 'url'=>array('create')),
	array('label'=>'View RestaurantCuisine', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RestaurantCuisine', 'url'=>array('admin')),
);
?>

<h1>Update RestaurantCuisine <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>