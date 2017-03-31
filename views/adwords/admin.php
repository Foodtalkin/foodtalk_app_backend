<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs=array(
	'links' => array('Adwords'),
);

$this->menu=array(
		array('label' => 'Manage','itemOptions' => array('class' => 'active' )),
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
$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Manage Adwords',
		)
);

$status = Yii::app()->request->getParam('status', false);
if(!$status)
	$urlPart = 'admin/status/';	
else 
	$urlPart = '';

$this->widget(
		'booster.widgets.TbTabs', array (
		'type' => 'tabs',
		'tabs' => array (
				array (
						'label' => 'All',
						'url' => 'all',
						'active' => !$status? true : $status == 'all' ? true : false
				),
				array (
						'label' => 'Active',
						'url' => $urlPart.'active',
						'active' => ($status == 'active' ? true : false) 
				),
				array (
						'label' => 'Upcomming',
						'url' => $urlPart.'upcomming',
						'active' => ($status == 'upcomming' ? true : false)
				),
				array (
						'label' => 'Expired',
						'url' => $urlPart.'expired' ,
						'active' => ($status == 'expired' ? true : false)
				),
				array (
						'label' => 'Disabled',
						'url' => $urlPart.'inactive',
						'active' => ($status == 'inactive' ? true : false)
				), 
		)
)
);
?>


<!-- <p> -->
<!-- 	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, -->
<!-- 	<b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the -->
<!-- 	beginning of each of your search values to specify how the comparison -->
<!-- 	should be done. -->
<!-- </p> -->



<?php // echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display: none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>
<!-- search-form -->

<?php $this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'adwords-grid',
		'type' => 'striped',
	'dataProvider'=>$model->search(),
// 	'filter'=>$model,
	'columns'=>array(
// 		'id',
		'title',
// 		'adType',
			
		array(
					'name'=>' Type ',
					'value'=>'$data->adType0->name', // link version
					'type'  => 'raw',
					'filter' => false
			),
		'entityId',
// 		'description',
// 		'startDate',
		array(
				'name'=>'startDate',
				'value' => 'date_format(date_create($data->startDate),"jS M Y")',
				'type'  => 'raw',
				'filter' => false
		),
			array(
					'name'=>'expiry',
					'value' => '$data->expiry?date_format(date_create($data->expiry),"jS M Y"):"N/A"',
					'type'  => 'raw',
					'filter' => false
			),
// 		'expiry',
		'cap',
// 		'latitude',
// 		'longitude',
		'priority',
		'position',
// 		'metaData',
// 		'isDisabled',
		/*
		'updateDate',
		'createId',
		'updateId',
		*/
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{view} {update} {delete}',	
		),
	),
)); ?>
<br><br>