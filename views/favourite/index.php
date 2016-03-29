<?php
/* @var $this FavouriteController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Favourites',
);

$this->menu=array(
	array('label'=>'Create Favourite', 'url'=>array('create')),
	array('label'=>'Manage Favourite', 'url'=>array('admin')),
);
?>

<h1>Favourites</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
