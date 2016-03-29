<?php
/* @var $this FavouriteController */
/* @var $model Favourite */

$this->breadcrumbs=array(
	'Favourites'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Favourite', 'url'=>array('index')),
	array('label'=>'Manage Favourite', 'url'=>array('admin')),
);
?>

<h1>Create Favourite</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>