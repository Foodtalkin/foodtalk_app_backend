<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

// $this->breadcrumbs=array(
// 	'Activity Points'=>array('index'),
// 	$model->id=>array('view','id'=>$model->id),
// 	'Update',
// );

// $this->menu=array(
// 	array('label'=>'List ActivityPoints', 'url'=>array('index')),
// 	array('label'=>'Create ActivityPoints', 'url'=>array('create')),
// 	array('label'=>'View ActivityPoints', 'url'=>array('view', 'id'=>$model->id)),
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
		array('label'=>'update', 'url'=>'update/'.$model->id ,'itemOptions' => array ('class' => 'active')),
		array('label'=>'View', 'url'=>$model->id),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Update : '.$model->id,
				'fixed' => false,
				'fluid' => true,
		)
);
?>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>