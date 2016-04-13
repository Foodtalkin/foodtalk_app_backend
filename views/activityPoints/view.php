<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

$this->breadcrumbs=array(
	'Activity Points'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ActivityPoints', 'url'=>array('index')),
	array('label'=>'Create ActivityPoints', 'url'=>array('create')),
	array('label'=>'Update ActivityPoints', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ActivityPoints', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ActivityPoints', 'url'=>array('admin')),
);
?>

<h1>View ActivityPoints #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'activityTable',
		'activityTitle',
		'platform',
		'activityDesc',
		'points',
		'timefactor',
		'minimum',
		'maximum',
		'penality',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
