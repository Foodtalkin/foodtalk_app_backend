<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

// $this->breadcrumbs=array(
// 	'Activity Points'=>array('index'),
// 	'Create',
// );

// $this->menu=array(
// 	array('label'=>'List ActivityPoints', 'url'=>array('index')),
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
		array('label'=>'Create', 'url'=>'create','itemOptions' => array ('class' => 'active')),
		// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
// 		array('label'=>'View Restaurant', 'url'=>'view/'.$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Add Activity Points',
				'fixed' => false,
				'fluid' => true,
		)
);

?>

<h1>Create ActivityPoints</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>