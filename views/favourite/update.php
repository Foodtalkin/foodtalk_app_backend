<?php
/* @var $this FavouriteController */
/* @var $model Favourite */

$this->breadcrumbs=array(
	'Favourites'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Favourite', 'url'=>array('index')),
	array('label'=>'Create Favourite', 'url'=>array('create')),
	array('label'=>'View Favourite', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Favourite', 'url'=>array('admin')),
);
?>

<h1>Update Favourite <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>