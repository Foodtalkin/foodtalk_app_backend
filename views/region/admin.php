<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Manage',
);

$this->menu=array(
	array('label'=>'New Region', 'url'=>array('create')),
	array('label'=>'Manage', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cuisine-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$type = Yii::app()->request->getParam('type',false);
?>

<h1>Manage Regions</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cuisine-grid',
	'dataProvider'=>$model->search($type),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'createDate',
		'updateDate',
                array(
                    'class'=>'CButtonColumn',
                    'template' => '{view}',
                    'buttons'=>array(
                    		'view' => array(
                    				'url'=>'Yii::app()->createAbsoluteUrl("region/get/", array("id"=>$data->id))',
                    		)
                    		
                    ),
                ),

	),
)); ?>
