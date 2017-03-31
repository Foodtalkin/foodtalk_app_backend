<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

// $this->breadcrumbs=array(
// 	'App Versions'=>array('index'),
// 	$model->id,
// );

$this->menu=array(
// 	array('label'=>'List AppVersion', 'url'=>array('index')),
// 	array('label'=>'Create AppVersion', 'url'=>array('create')),
	array('label'=>'Update this'),
// 	array('label'=>'Delete AppVersion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AppVersion', 'url'=>'admin'),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'View #'.$model->id,
		)
);

?>



<?php $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'platform',
		'version',
		'message',
		'isCritical',
	),
)); ?>
