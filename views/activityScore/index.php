<?php
/* @var $this ActivityScoreController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Activity Scores',
);

$this->menu=array(
	array('label'=>'Create ActivityScore', 'url'=>array('create')),
	array('label'=>'Manage ActivityScore', 'url'=>array('admin')),
);
?>

<h1>Activity Scores</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
