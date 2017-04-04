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


$this->breadcrumbs = array (
		'links' => array (
				'Regions'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin','itemOptions' => array ('class' => 'active')),
		// 		array('label'=>'Create Restaurant', 'url'=>'create'),
// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
// 		array('label'=>'View Restaurant', 'url'=>'view/'.$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Manage Regions',
				'fixed' => false,
				'fluid' => true,
		)
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

<?php $this->widget('booster.widgets.TbExtendedGridView', array(
	'type' => 'striped condensed',
	'id'=>'cuisine-grid',
	'dataProvider'=>$model->search($type),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'createDate',
		'updateDate',
                array(
                	'class'=>'booster.widgets.TbButtonColumn',
//                     'class'=>'CButtonColumn',
                    'template' => '{view}',
                    'buttons'=>array(
                    		'view' => array(
                    				'url'=>'Yii::app()->createAbsoluteUrl("region/get/", array("id"=>$data->id))',
                    		)
                    		
                    ),
                ),

	),
)); ?>
