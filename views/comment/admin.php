<?php
/* @var $this CommentController */
/* @var $model Comment */

$this->breadcrumbs=array(
	'Comments'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Comment', 'url'=>array('admin')),
// 	array('label'=>'Disabled Comment', 'url'=>array('disabled')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#comment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
$action = Yii::app()->controller->action->id;
$type = Yii::app()->request->getParam('type',false);
// echo $action
?>

<h1>Manage Comments</h1>
	<ul class="nav nav-tabs">
	  <li<?php if($action=='admin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("comment/admin"); ?>">All</a></li>
	  <li<?php if($action=='reported') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("comment/reported"); ?>">Reported </a></li>
	  <li<?php if($action=='disabled') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("comment/disabled"); ?>">Disabled</a></li>

	</ul>


<?php
// echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php 
// $this->renderPartial('_search',array('model'=>$model,)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'comment-grid',
	'dataProvider'=>$model->search($type),
	'filter'=>$model,
	'columns'=>array(
// 		'id',
		array(
			'name'=>'userId',
			'value'=>'(isset($data->user))? CHtml::link($data->user->userName,array("user/".$data->userId)):""', // link version
				
// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
			'type'  => 'raw',
			'filter' => false
		),
			
// 		'postId',
			array(
					'name' => 'post',
					'type' => 'raw',
					'value' => '(!empty($data->post->tip))?  $data->post->tip : ""',
					'filter' => false
			),
				
			array(
					'name' => 'image',
					'type' => 'raw',
					'value' => '(!empty($data->post->image))? CHtml::image(thumbPath("post") . $data->post->image, "") : ""',
					'filter' => false
			),
				
			array(
					'name'=>'postUserId',
					'value'=>'(isset($data->postUser))? CHtml::link($data->postUser->userName,array("user/".$data->postUserId)):""', // link version
			
					// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
					'type'  => 'raw',
					'filter' => false
			),
				
// 		'postUserId',
		'comment',
		'isDisabled',
		/*
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
		*/
		array(
			'class'=>'CButtonColumn',
				'template' => ' {delete}',
		),
	),
)); ?>
