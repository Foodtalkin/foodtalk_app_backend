<?php
/* @var $this AppVersionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'App Versions',
);

$this->menu=array(
	array('label'=>'Create AppVersion', 'url'=>array('create')),
	array('label'=>'Manage AppVersion', 'url'=>array('admin')),
);
?>

<h1>App Versions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
