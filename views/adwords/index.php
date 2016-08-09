<?php
/* @var $this AdwordsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Adwords',
);

$this->menu=array(
	array('label'=>'Create Adwords', 'url'=>array('create')),
	array('label'=>'Manage Adwords', 'url'=>array('admin')),
);
?>

<h1>Adwords</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
