<?php
/* @var $this RestaurantCuisineController */
/* @var $model RestaurantCuisine */

$this->breadcrumbs=array(
	'Restaurant Cuisines'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List RestaurantCuisine', 'url'=>array('index')),
	array('label'=>'Create RestaurantCuisine', 'url'=>array('create')),
	array('label'=>'Update RestaurantCuisine', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RestaurantCuisine', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RestaurantCuisine', 'url'=>array('admin')),
);
?>

<h1>View RestaurantCuisine #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'restaurantId',
		'cuisineId',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
