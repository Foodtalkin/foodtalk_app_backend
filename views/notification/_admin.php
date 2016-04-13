<h1>Manage Notification</h1>
<!-- <p> -->
<!-- You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> -->
<!-- or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done. -->
<!-- </p> -->

<?php 
$action = Yii::app()->controller->action->id;
?>
	<ul class="nav nav-tabs">
	  <li<?php if($action=='admin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("notification/admin"); ?>">All</a></li>
	  <li<?php if($action=='pending') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("notification/pending"); ?>">Pending </a></li>
	  <li<?php if($action=='notified') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("notification/notified"); ?>">Notified</a></li>

	</ul>



<?php
// Yii::app()->createUrl("user/".$data->userId)



	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'post-grid',
	'dataProvider'=>$model->search('public'),
	'filter'=>$model,
	'columns'=>array(
			'id','eventType','message','eventDate', 'createDate',			
			array(
                    'class'=>'CButtonColumn',
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
