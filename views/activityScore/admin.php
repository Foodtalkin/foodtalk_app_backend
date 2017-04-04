<?php
/* @var $this ActivityScoreController */
/* @var $model ActivityScore */

// $this->breadcrumbs=array(
// 	'Activity Scores'=>array('index'),
// 	'Manage',
// );

// $this->menu=array(
// 	array('label'=>'Manage ActivityScore', 'url'=>array('admin')),
		
// // 	array('label'=>'List ActivityScore', 'url'=>array('index')),
// // 	array('label'=>'Create ActivityScore', 'url'=>array('create')),
// );

$this->breadcrumbs = array (
		'links' => array (
				'Activity Scores'
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
				'brand' => 'Manage Activity Scores',
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
	$('#activity-score-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->widget('booster.widgets.TbExtendedGridView', array(
		'type' => 'striped condensed',
	'id'=>'activity-score-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'facebookId',
		'avilablePoints',
		'totalPoints',
		'score',
		'createDate',
		'updateDate',
		/*
		'createId',
		'updateId',
		*/
		array(
                    'class'=>'booster.widgets.TbButtonColumn',
                    'template' => '{view}',
				
		),
	),
)); ?>
