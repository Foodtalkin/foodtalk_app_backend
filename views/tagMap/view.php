<?php
/* @var $this TagMapController */
/* @var $model TagMap */

$this->breadcrumbs=array(
	'Tag Maps'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TagMap', 'url'=>array('index')),
	array('label'=>'Create TagMap', 'url'=>array('create')),
	array('label'=>'Update TagMap', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TagMap', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TagMap', 'url'=>array('admin')),
);
?>

<h1>View TagMap #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tagId',
		'postId',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
