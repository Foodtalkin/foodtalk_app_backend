<?php
/* @var $this FollowerController */
/* @var $model Follower */

$this->breadcrumbs=array(
	'Followers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Follower', 'url'=>array('index')),
	array('label'=>'Create Follower', 'url'=>array('create')),
	array('label'=>'Update Follower', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Follower', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Follower', 'url'=>array('admin')),
);
?>

<h1>View Follower #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'followerUserId',
		'followedUserId',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
