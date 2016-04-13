<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */

$this->breadcrumbs=array(
	'Activity Scores'=>array('index'),
	$model->facebookId=>array('view','id'=>$model->facebookId),
	'Update',
);

$this->menu=array(
	array('label'=>'List ActivityScore', 'url'=>array('index')),
	array('label'=>'Create ActivityScore', 'url'=>array('create')),
	array('label'=>'View ActivityScore', 'url'=>array('view', 'id'=>$model->facebookId)),
	array('label'=>'Manage ActivityScore', 'url'=>array('admin')),
);
?>

<h1>Update ActivityScore <?php echo $model->facebookId; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>