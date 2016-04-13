<?php
/* @var $this RestaurantCuisineController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Restaurant Cuisines',
);

$this->menu=array(
	array('label'=>'Create RestaurantCuisine', 'url'=>array('create')),
	array('label'=>'Manage RestaurantCuisine', 'url'=>array('admin')),
);
?>

<h1>Restaurant Cuisines</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
