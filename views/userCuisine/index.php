<?php
/* @var $this UserCuisineController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Cuisines',
);

$this->menu=array(
	array('label'=>'Create UserCuisine', 'url'=>array('create')),
	array('label'=>'Manage UserCuisine', 'url'=>array('admin')),
);
?>

<h1>User Cuisines</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
