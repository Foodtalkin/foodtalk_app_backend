<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs=array(
	'links' => array('Adwords'),
);

$this->menu=array(
		array(
				'label' => 'Operations',
				'itemOptions' => array('class' => 'nav-header')
		),
		array('label' => 'Manage', 'url' => 'admin','itemOptions' => array('class' => 'active' )),
		array('label' => 'Create','url' => 'create')
		,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#adwords-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Adwords</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'adwords-grid',
		'type' => 'striped',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
// 		'id',
		'title',
// 			array(
// 					'name' => 'image',
// 					'type' => 'raw',
// 					'value' => '(!empty($data->image))? CHtml::image(thumbPath("post") . $data->image, "") : " "',
// 					'filter' => false
// 			),
		'entityId',
		'actionButtonText',
		'description',
		'startDate',
		'expiry',
		'cap',
		'latitude',
		'longitude',
		'priority',
		'position',
		'metaData',
		'isDisabled',
		/*
		'updateDate',
		'createId',
		'updateId',
		*/
		array(
			'class'=>'booster.widgets.TbButtonColumn',
		),
	),
)); ?>
