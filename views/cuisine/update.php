<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

// $this->breadcrumbs=array(
// 	'Cuisines'=>array('admin'),
// 	$model->id=>array('view','id'=>$model->id),
// 	'Update',
// );

// $this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Create Cuisine', 'url'=>array('create')),
// 	array('label'=>'View Cuisine', 'url'=>array('view', 'id'=>$model->id)),
// 	array('label'=>'Manage Cuisine', 'url'=>array('admin')),
// );
?>

<h1>Update Cuisine: <?php echo $model->cuisineName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>