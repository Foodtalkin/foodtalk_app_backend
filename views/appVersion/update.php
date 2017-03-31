<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

// $this->breadcrumbs=array(
// 	'App Versions'=>array('index'),
// 	$model->id=>array('view','id'=>$model->id),
// 	'Update',
// );

// $this->menu=array(
// // 	array('label'=>'List AppVersion', 'url'=>array('index')),
// // 	array('label'=>'Create AppVersion', 'url'=>array('create')),
// // 	array('label'=>'View AppVersion', 'url'=>array('view', 'id'=>$model->id)),
// 	array('label'=>'Manage AppVersion', 'url'=>array('admin')),
// );


$this->breadcrumbs = array (
		'links' => array (
				'App Versions' => 'admin',
				'Update'
		)
);

$this->menu = array (

		array (
				'label' => 'Manage',
				'url' => 'admin'
		),
		array (
				'label' => 'Edit',
				'itemOptions' => array (
						'class' => 'active'
				)
		)
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Update  #'.$model->platform,
		)
);


?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>