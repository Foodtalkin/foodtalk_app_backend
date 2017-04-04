<?php
/* @var $this ActivityPointsController */
/* @var $model ActivityPoints */

// $this->breadcrumbs=array(
// 	'Activity Points'=>array('index'),
// 	'Manage',
// );

// $this->menu=array(
// 	array('label'=>'List ActivityPoints', 'url'=>array('index')),
// 	array('label'=>'Create ActivityPoints', 'url'=>array('create')),
// );


$this->breadcrumbs = array (
		'links' => array (
				'ActivityPoints'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin','itemOptions' => array ('class' => 'active')),
		array('label'=>'Create', 'url'=>'create'),
// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
// 		array('label'=>'View Restaurant', 'url'=>'view/'.$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Manage Activity Points',
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
	$('#activity-points-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Activity Points</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php


// echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php
// $this->renderPartial('_search',array(
// 	'model'=>$model,
// )); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbExtendedGridView', array(
		'type' => 'striped condensed',
	'id'=>'activity-points-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
// 		'id',
		'activityTable',
		'activityTitle',
		'platform',
// 		'activityDesc',
		'points',
		'timefactor',
		'minimum',
		'maximum',
		'penality',
// 		'isDisabled',
// 		'disableReason',
// 		'createDate',
// 		'updateDate',
// 		'createId',
// 		'updateId',
		array(
				'class'=>'booster.widgets.TbButtonColumn',
// 			'class'=>'CButtonColumn',
		),
	),
)); ?>
