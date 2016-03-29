<?php
/* @var $this FlagController */
/* @var $model Flag */

$this->breadcrumbs=array(
	'Flags'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Flag', 'url'=>array('index')),
	array('label'=>'Create Flag', 'url'=>array('create')),
	array('label'=>'Update Flag', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Flag', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Flag', 'url'=>array('admin')),
);
?>

<h1>View Flag #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userId',
		'postId',
		'postUserId',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
