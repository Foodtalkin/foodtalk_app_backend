<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs=array(
	'App Versions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
// 	array('label'=>'List AppVersion', 'url'=>array('index')),
// 	array('label'=>'Create AppVersion', 'url'=>array('create')),
// 	array('label'=>'View AppVersion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AppVersion', 'url'=>array('admin')),
);
?>

<h1>Update AppVersion <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>