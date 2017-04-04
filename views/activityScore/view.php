<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */

// $this->breadcrumbs=array(
// 	'Activity Scores'=>array('index'),
// 	$model->facebookId,
// );

// $this->menu=array(
// // 	array('label'=>'List ActivityScore', 'url'=>array('index')),
// // 	array('label'=>'Create ActivityScore', 'url'=>array('create')),
// // 	array('label'=>'Update ActivityScore', 'url'=>array('update', 'id'=>$model->facebookId)),
// // 	array('label'=>'Delete ActivityScore', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->facebookId),'confirm'=>'Are you sure you want to delete this item?')),
// 	array('label'=>'Manage ActivityScore', 'url'=>array('admin')),
// );


$this->breadcrumbs = array (
		'links' => array (
				'Activity Scores'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin'),
		// 		array('label'=>'Create Restaurant', 'url'=>'create'),
// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
		array('label'=>'View Restaurant', 'url'=>$model->facebookId,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Activity Scores : '.$model->facebookId,
				'fixed' => false,
				'fluid' => true,
		)
);
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'facebookId',
		'avilablePoints',
		'totalPoints',
		'score',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
)); ?>
<br>