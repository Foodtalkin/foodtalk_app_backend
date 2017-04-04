<?php
/* @var $this FlagController */
/* @var $model Flag */

// $this->breadcrumbs=array(
// 	'Region'=>array('admin'),
// 	'Create',
// );

// $this->menu=array(
// // 	array('label'=>'List Flag', 'url'=>array('index')),
// 	array('label'=>'Manage', 'url'=>array('admin')),
// );
$this->breadcrumbs = array (
		'links' => array (
				'Regions'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin'),
		array('label'=>'Add Region', 'url'=>'create','itemOptions' => array ('class' => 'active')),
		// 		array('label'=>'Delete Region', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
// 		array('label'=>'View Region', 'url'=>$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Create Region',
				'fixed' => false,
				'fluid' => true,
		)
);

?>

<h1>Create Region</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>