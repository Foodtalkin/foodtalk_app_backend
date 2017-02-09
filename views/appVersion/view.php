<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs=array(
	'App Versions'=>array('index'),
	$model->id,
);

$this->menu=array(
// 	array('label'=>'List AppVersion', 'url'=>array('index')),
// 	array('label'=>'Create AppVersion', 'url'=>array('create')),
	array('label'=>'Update this', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete AppVersion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AppVersion', 'url'=>array('admin')),
);
?>

<h1>View AppVersion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'platform',
		'version',
		'message',
		'isCritical',
	),
)); ?>
