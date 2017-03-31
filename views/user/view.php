<?php
/* @var $this UserController */
/* @var $model User */

// $this->breadcrumbs=array(
// 	'Users'=>array('admin'),
// 	$model->id,
// );
$this->breadcrumbs=array(
		'links' => array( 'Users'),
		// 		'Users'
);

$this->menu=array(
//	array('label'=>'List User', 'url'=>array('index')),
//	array('label'=>'Create User', 'url'=>array('create')),
//	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage User', 'url'=>'admin'),
	array('label' => 'VIew','itemOptions' => array('class' => 'active' )),
	array('label'=>'Delete User', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this user?')),
	array('label'=>'Restore User', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this user?')),
);

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'User : '.$model->userName,
		)
);
 $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userName',
		'email',
		'userCity.cityName',
		'userCity.region.regionName',
		'fullName',
		'createDate',
		array('type'=>'image','value'=>imagePath('user') . $model->image, 'label'=>'Image'),
		'sendPushNotification',
		'shareOnFacebook',
		'facebookId',
// 		'shareOnInstagram',
		'reportedCount'	
	),
)); 

$type = Yii::app()->request->getParam('type',false);

?>
<br>
<ul class="nav nav-tabs">
  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("user/".$model->id); ?>">All</a></li>
  <li<?php if($type=='checkin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("user/view/id/".$model->id."/type/checkin"); ?>">Check-in</a></li>
  <li<?php if($type=='reviews') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("user/view/id/".$model->id."/type/reviews"); ?>">Reviews</a></li> 
  <li<?php if($type=='images') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("user/view/id/".$model->id."/type/images"); ?>">Images</a></li>
  <li<?php if($type=='post') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("user/view/id/".$model->id."/type/post"); ?>">Posts</a></li>
  <li<?php if($type=='reported') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("user/view/id/".$model->id."/type/reported"); ?>">Reported</a></li>
  
</ul>
<?php 


$options = array();
$options['user'] = $model->id;


$this->widget('booster.widgets.TbExtendedGridView', array(
		'type' => 'striped condensed',
		'id'=>'post-grid',
		'dataProvider'=>$posts->search($type, true, $options ),
		'filter'=>$posts,
		'columns'=>array(
				array(
						'name'=>'reportedCount',
						'filter' => false
				),
				array(
						'name'=>'Dish Name',
						'value'=>'(isset($data->dishReview))? CHtml::link($data->dishReview->dish->dishName,array("dish/".$data->dishReview->dishId)):""', // link version
							
						// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
						'type'  => 'raw',
						'filter' => false
				),
				// 		'restaurantName',
// 				array(
// 						'name'=>'userId',
// 						'value'=>'(isset($data->user))? CHtml::link($data->user->userName,array("user/".$data->userId)):""', // link version

// 						// 			'value' => 'CHtml::link($data->userId, Yii::app()->createUrl("user/".$data->userId))',
// 						'type'  => 'raw',
// 						'filter' => false
// 				),
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
		'tip',
		array(
   			'class'=>'booster.widgets.TbButtonColumn',
				'template' => '{view} {delete} {restore}',
				'buttons'=>array(
						'view' => array(
								'url'=>'Yii::app()->createAbsoluteUrl("post/".$data->id)',
						),
						'delete' => array(
								'visible'=>'!$data->isDisabled',
								'url'=>'Yii::app()->createAbsoluteUrl("post/delete/".$data->id)',
						),
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
								//'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
								//'options' => array('class'=>'copy'), // HTML options for the button
						),
		),
),
),
));

?>
<br><br>