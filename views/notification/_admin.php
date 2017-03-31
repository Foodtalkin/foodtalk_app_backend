<!-- <p> -->
<!-- You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> -->
<!-- or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done. -->
<!-- </p> -->

<?php
$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Manage Notification',
		)
);

$action = Yii::app()->controller->action->id;

$this->widget(
		'booster.widgets.TbTabs', array (
				'type' => 'tabs',
				'tabs' => array (
						array (
								'label' => 'All',
								'url' => 'admin',
								'active' => ($action == 'admin' ? true : false)
						),
						array (
								'label' => 'Pending',
								'url' => 'pending',
								'active' => ($action == 'pending' ? true : false)
						),
						array (
								'label' => 'Notified',
								'url' => 'notified' ,
								'active' => ($action == 'notified' ? true : false)
						),
				)
		)
);


	$this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'post-grid',
	'type' => 'striped condensed',
	'dataProvider'=>$model->search('public'),
// 	'filter'=>$model,
	'columns'=>array(
			'id','eventType','message','eventDate', 'createDate',			
			array(
					'class'=>'booster.widgets.TbButtonColumn',
                    'template' => '{view} {delete}',
                    'buttons'=>array(
                        'delete' => array( 'visible'=>'!$data->isNotified'),
                    	'update' => array( 'url'=>'Yii::app()->createAbsoluteUrl("notification/".$data->id)',),
                        'restore' => array(
                            'label'=> 'R',
                            'url'=>'Yii::app()->createAbsoluteUrl("item/restore", array("id"=>$data->id))',
                            'visible'=>'$data->isDisabled',
                            'click'=>"function(){
                                if(!confirm('Are you sure to restore this item?')) return false;
                                $.fn.yiiGridView.update('item-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                        $.fn.yiiGridView.update('item-grid');
                                    }
                                })
                                return false;
                            }",

                        ),
                    ),
                )
	),
)); ?>
