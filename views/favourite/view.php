<?php
/* @var $this FavouriteController */
/* @var $model Favourite */

$this->breadcrumbs=array(
	'Favourites'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Favourite', 'url'=>array('index')),
	array('label'=>'Create Favourite', 'url'=>array('create')),
	array('label'=>'Update Favourite', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Favourite', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Favourite', 'url'=>array('admin')),
);
?>

<h1>View Favourite #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userId',
		'restaurantId',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
