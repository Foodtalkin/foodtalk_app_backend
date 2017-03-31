<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs = array (
		
		'links' => array (
				'Adwords' => 'admin',
				'create' 
		) 
);

$this->menu = array (

		array (
				'label' => 'Manage',
				'url' => 'admin' 
		),
		array (
				'label' => 'Create',
				'url' => 'create',
		),
		array (
				'label' => 'Edit',
				'url' => 'update/'.$model->id,
		),
		array (
				'label' => 'View',
				'itemOptions' => array (
						'class' => 'active'
				)
		),
);

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'View Ad #'.$model->id,
		)
);

?>


<?php $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'adType',
			
// 		array(
// 					'name'=>'adType',
// 					'value'=>'$data->adType0->name', // link version
// 					'type'  => 'raw',
// 					'filter' => false
// 			),
		'entityId',
		'actionButtonText',	
		'description',
		'startDate',
// 		array(
// 				'name'=>'startDate',
// 				'value' => 'date_format(date_create($data->startDate),"jS M Y")',
// 				'type'  => 'raw',
// 				'filter' => false
// 		),
// 			array(
// 					'name'=>'startDate',
// 					'value' => 'date_format(date_create($data->expiry),"jS M Y")',
// 					'type'  => 'raw',
// 					'filter' => false
// 			),
		'expiry',
		'cap',
		'latitude',
		'longitude',
		'priority',
		'position',
		'metaData',
		'isDisabled',
		'updateDate',
		'createId',
		'updateId',
			
	),
)); ?>
