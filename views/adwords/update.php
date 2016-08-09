<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs=array(
	'Adwords'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
// 	array('label'=>'List Adwords', 'url'=>array('index')),
	array('label'=>'Create Adwords', 'url'=>array('create')),
	array('label'=>'View Adwords', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Adwords', 'url'=>array('admin')),
);
?>

<h1>Update Adwords <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>