<?php
/* @var $this FlagController */
/* @var $model Flag */

$this->breadcrumbs=array(
	'Flags'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Flag', 'url'=>array('index')),
	array('label'=>'Create Flag', 'url'=>array('create')),
	array('label'=>'View Flag', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Flag', 'url'=>array('admin')),
);
?>

<h1>Update Flag <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>