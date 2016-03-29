<?php
/* @var $this TagMapController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tag Maps',
);

$this->menu=array(
	array('label'=>'Create TagMap', 'url'=>array('create')),
	array('label'=>'Manage TagMap', 'url'=>array('admin')),
);
?>

<h1>Tag Maps</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
