<?php
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs=array(
	'Posts'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'New Post', 'url'=>array('post/create')),	
// 	array('label'=>'List Post', 'url'=>array('index')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
);
?>

<h1>Create Post</h1>

<?php $this->renderPartial('_new', array('model'=>$model)); ?>