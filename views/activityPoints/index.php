<?php
/* @var $this ActivityPointsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Activity Points',
);

$this->menu=array(
	array('label'=>'Create ActivityPoints', 'url'=>array('create')),
	array('label'=>'Manage ActivityPoints', 'url'=>array('admin')),
);
?>

<h1>Activity Points</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
