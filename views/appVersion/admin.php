<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs = array (
		'links' => array (
				'App Versions'
		)
);

$this->menu = array (

		array (
				'label' => 'Manage',
				'url' => 'admin',
				'itemOptions' => array (
						'class' => 'active'
				)
		)
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#app-version-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Manage App Versions  ',
		)
);
?>


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

<?php
$this->widget('booster.widgets.TbExtendedGridView', array(
// $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'app-version-grid',
	'dataProvider'=>$model->search(),
		
	'filter'=>$model,
	'columns'=>array(
// 		'id',
		'platform',
		'version',
		'message',
// 		'isCritical',
			array(
					'name'=>'isCritical',
					'value' => '$data->isCritical?"Yes":"No"',
					'type'  => 'raw',
// 					'filter' => false
			),
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}',	
		),
	),
)); ?>
