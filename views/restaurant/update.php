<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->breadcrumbs=array(
	'Restaurants'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
	array('label'=>'Create Restaurant', 'url'=>array('create')),
	array('label'=>'View Restaurant', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Restaurant', 'url'=>array('admin')),
);
?>

<h1>Update Restaurant: <?php echo $model->restaurantName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>