<?php
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs=array(
	'Posts'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'New Post', 'url'=>array('post/create')),
	array('label'=>'Active Post', 'url'=>array('post/admin')),
	array('label'=>'Disabled Post', 'url'=>array('post/disabled')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#post-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$action = Yii::app()->controller->action->id;
$type = Yii::app()->request->getParam('type',false);

?>

<h1>Manage Posts</h1>
<!-- <p> -->
<!-- You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> -->
<!-- or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done. -->
<!-- </p> -->

<ul class="nav nav-tabs">
  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action); ?>">All</a></li>
    <li<?php if($type=='inactive') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=inactive"); ?>">Post on New Resturants</a></li>
  <li<?php if($type=='checkin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=checkin"); ?>">Check-in</a></li>
  <li<?php if($type=='reviews') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=reviews"); ?>">Reviews</a></li> 
  <li<?php if($type=='images') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=images"); ?>">Images</a></li>
  <li<?php if($type=='post') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=post"); ?>">Posts</a></li>  
  <li<?php if($type=='reported') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=reported"); ?>">Reported</a></li>
  
</ul>
<?php if($type=='reported') { 
$status = Yii::app()->request->getParam('status',false);
?>

	
	<ul class="nav nav-tabs">
	  <li<?php if(!$status) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=reported"); ?>">Pending</a></li>
	  <li<?php if($status=='approved') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=reported&status=approved"); ?>">Approved</a></li>
	  <li<?php if($status=='rejected') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("post/".$action."?type=reported&status=rejected"); ?>">Rejected</a></li>

	</ul>
<?php }?>	
<?php
// Yii::app()->createUrl("user/".$data->userId)



	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'post-grid',
	'dataProvider'=>$model->search($type, true),
	'filter'=>$model,
	'columns'=>array(
		array(
				'name'=>'reportedCount',
				'filter' => false
			),			
// 		'restaurantName',
			array(
					'name'=>'Dish Name',
					'value'=>'(isset($data->dishReview))? CHtml::link($data->dishReview->dish->dishName,array("dish/".$data->dishReview->dishId)):""', // link version
			
					// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
					'type'  => 'raw',
					'filter' => false
			),
		array(
			'name'=>'userId',
			'value'=>'(isset($data->user))? CHtml::link($data->user->userName,array("user/".$data->userId)):""', // link version
				
// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
			'type'  => 'raw',
			'filter' => false
		),
// 		'checkedInRestaurantId',
		array(
			'name'=>'checkedInRestaurantId',				
			'value'=>'(isset($data->checkedInRestaurant))? CHtml::link($data->checkedInRestaurant->restaurantName,array("restaurant/".$data->checkedInRestaurantId)):""', // link version
// 			'filter' => CHtml::listData(Restaurant::model()->findAll(), 'id', 'restaurantName'), // fields from country table
			'type'  => 'raw',
			'filter' => false	
		),
// 		'image',
// 			array('type'=>'image','value'=> imagePath('post') . $model->image, 'label'=>'Image'),
			array(
					'name' => 'image',
					'type' => 'raw',
					'value' => '(!empty($data->image))? CHtml::image(thumbPath("post") . $data->image, "") : " "',
					'filter' => false
			),
		array('name'=>'checkedInRestaurant.city.cityName', 'value'=>'isset($data->checkedInRestaurant->city->cityName)? $data->checkedInRestaurant->city->cityName : (isset($data->user->userCity->cityName) ? $data->user->userCity->cityName : "")'  ),	
// 		'checkedInRestaurant.city.cityName',	
		'tip',
		array(
                    'class'=>'CButtonColumn',
//                     'template' => '{view} {delete} {restore} {approve}',
					'template' => '{view} {delete} {approve}',
                    'buttons'=>array(
                        'delete' => array(
                            'visible'=>'!$data->isDisabled',
                        ),
                    	'approve' => array(
                    				'label'=>'✓', 
//                     			CHtml::image('http://wcdn3.dataknet.com/static/resources/icons/set29/3e4058cd.png', 'approve'), 
//                     				'imageUrl'=>'http://wcdn3.dataknet.com/static/resources/icons/set29/3e4058cd.png',
                    				'url'=>'Yii::app()->createAbsoluteUrl("post/approve", array("id"=>$data->id))',
                    				'visible'=>'$data->reportedCount',
                    				'click'=>"function(){
                                if(!confirm('Approve this post ?')) return false;
                                $.fn.yiiGridView.update('post-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                        $.fn.yiiGridView.update('post-grid');
                                    }
                                })
                                return false;
                            }",
                    				//'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
                    				//'options' => array('class'=>'copy'), // HTML options for the button
                    		),
                        'restore' => array(
                            'label'=> 'R',
                            'url'=>'Yii::app()->createAbsoluteUrl("item/restore", array("id"=>$data->id))',
                            'visible'=>'$data->isDisabled',
                            'click'=>"function(){
                                if(!confirm('Are you sure to restore this item?')) return false;
                        		console.log(this);
                                $.fn.yiiGridView.update('item-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                        $.fn.yiiGridView.update('item-grid');
                                    }
                                })
                                return false;
                            }",
                            //'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
                            //'options' => array('class'=>'copy'), // HTML options for the button
                        ),
                    ),
                ),
	),
)); ?>
