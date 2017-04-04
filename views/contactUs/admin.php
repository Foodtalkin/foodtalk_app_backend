<?php
/* @var $this CityController */
/* @var $model City */

// $this->breadcrumbs=array(
// // 	'Cities'=>array('admin'),
// 	'Contact Us',
// );

$this->breadcrumbs=array(
		'links' => array( 'Contact Us'),
		// 		'Users'
);

$this->menu=array(
	array('label'=>'Manage', 'url'=>'admin' ,'itemOptions' => array('class' => 'active' ))
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#city-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Contact Us',
		)
);
$this->widget('booster.widgets.TbExtendedGridView', array(
		'type' => 'striped',
	'id'=>'city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
// 		'id',
		'message',
			array(
					'name'=>'userId',
					'value'=>'(isset($data->user))? CHtml::link($data->user->userName,array("user/".$data->userId)):""', // link version
			
					// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
					'type'  => 'raw',
					'filter' => false
			),
			array(
					'name'=>'userId',
					'value'=>'$data->user->email', // link version
						
					// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
					'type'  => 'raw',
					'filter' => false
			),
				
// 		array(
//                     'class'=>'CButtonColumn',
//                     'template' => '{view} {update} {delete} {restore}',
//                     'buttons'=>array(
//                         'delete' => array(
//                             'visible'=>'!$data->isDisabled',
//                         ),
//                         'restore' => array(
//                             'label'=> 'R',
//                             'url'=>'Yii::app()->createAbsoluteUrl("item/restore", array("id"=>$data->id))',
//                             'visible'=>'$data->isDisabled',
//                             'click'=>"function(){
//                                 if(!confirm('Are you sure to restore this item?')) return false;
//                                 $.fn.yiiGridView.update('item-grid', {
//                                     type:'POST',
//                                     url:$(this).attr('href'),
//                                     success:function(data) {
//                                         $.fn.yiiGridView.update('item-grid');
//                                     }
//                                 })
//                                 return false;
//                             }",
//                             //'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
//                             //'options' => array('class'=>'copy'), // HTML options for the button
//                         ),
//                     ),
//                 ),
	),
)); ?>
<br><br>