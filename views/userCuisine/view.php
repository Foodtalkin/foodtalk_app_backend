<?php
/* @var $this UserCuisineController */
/* @var $model UserCuisine */

$this->breadcrumbs=array(
	'User Cuisines'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserCuisine', 'url'=>array('index')),
	array('label'=>'Create UserCuisine', 'url'=>array('create')),
	array('label'=>'Update UserCuisine', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserCuisine', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserCuisine', 'url'=>array('admin')),
);
?>

<h1>View UserCuisine #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userId',
		'cuisineId',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
