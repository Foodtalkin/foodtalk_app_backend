<?php
/* @var $this UserCuisineController */
/* @var $model UserCuisine */

$this->breadcrumbs=array(
	'User Cuisines'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserCuisine', 'url'=>array('index')),
	array('label'=>'Create UserCuisine', 'url'=>array('create')),
	array('label'=>'View UserCuisine', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserCuisine', 'url'=>array('admin')),
);
?>

<h1>Update UserCuisine <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>