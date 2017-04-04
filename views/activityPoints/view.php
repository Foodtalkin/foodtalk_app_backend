<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

// $this->breadcrumbs=array(
// 	'Activity Points'=>array('index'),
// 	$model->id,
// );

// $this->menu=array(
// 	array('label'=>'List ActivityPoints', 'url'=>array('index')),
// 	array('label'=>'Create ActivityPoints', 'url'=>array('create')),
// 	array('label'=>'Update ActivityPoints', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete ActivityPoints', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 	array('label'=>'Manage ActivityPoints', 'url'=>array('admin')),
// );


$this->breadcrumbs = array (
		'links' => array (
				'ActivityPoints'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin'),
		array('label'=>'Create', 'url'=>'create'),
		array('label'=>'update', 'url'=>'update/'.$model->id ),
		array('label'=>'View', 'url'=>$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Activity Point  #'.$model->id,
				'fixed' => false,
				'fluid' => true,
		)
);
?>


<?php  $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'activityTable',
		'activityTitle',
		'platform',
		'activityDesc',
		'points',
		'timefactor',
		'minimum',
		'maximum',
		'penality',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
<h1></h1>
