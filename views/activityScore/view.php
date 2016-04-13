<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */

$this->breadcrumbs=array(
	'Activity Scores'=>array('index'),
	$model->facebookId,
);

$this->menu=array(
// 	array('label'=>'List ActivityScore', 'url'=>array('index')),
// 	array('label'=>'Create ActivityScore', 'url'=>array('create')),
// 	array('label'=>'Update ActivityScore', 'url'=>array('update', 'id'=>$model->facebookId)),
// 	array('label'=>'Delete ActivityScore', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->facebookId),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ActivityScore', 'url'=>array('admin')),
);
?>

<h1>View ActivityScore #<?php echo $model->facebookId; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'facebookId',
		'avilablePoints',
		'totalPoints',
		'score',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
