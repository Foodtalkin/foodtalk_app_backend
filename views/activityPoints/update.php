<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

$this->breadcrumbs=array(
	'Activity Points'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ActivityPoints', 'url'=>array('index')),
	array('label'=>'Create ActivityPoints', 'url'=>array('create')),
	array('label'=>'View ActivityPoints', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ActivityPoints', 'url'=>array('admin')),
);
?>

<h1>Update ActivityPoints <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>