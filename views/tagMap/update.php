<?php
/* @var $this TagMapController */
/* @var $model TagMap */

$this->breadcrumbs=array(
	'Tag Maps'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TagMap', 'url'=>array('index')),
	array('label'=>'Create TagMap', 'url'=>array('create')),
	array('label'=>'View TagMap', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TagMap', 'url'=>array('admin')),
);
?>

<h1>Update TagMap <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>