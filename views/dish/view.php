<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />
<?php
/* @var $this UserController */
/* @var $model User */

// $this->breadcrumbs=array(
// 	'dish'=>array('admin'),
// 	$model->id,
// );

$this->breadcrumbs = array (
		'links' => array (
				'Dishes'=>'admin',
				$model->dishName
				
		)
);


$this->menu=array(
//	array('label'=>'List User', 'url'=>array('index')),
//	array('label'=>'Create User', 'url'=>array('create')),
//	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete Dish', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this user?')),
// 	array('label'=>'Restore Dish', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this user?')),
	array('label'=>'Manage Dish', 'url'=>'admin', 
			
// 			'itemOptions' => array('class' => 'active' ) 
			
			
),
	array('label'=>'view', 'url'=>'admin','itemOptions' => array('class' => 'active' )),
);
$type = Yii::app()->request->getParam('type',false);

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Dish : '.$model->dishName,
		)
);
?>

<form method="post">
<table class="general" id="yw0"><tbody>

<tr class="odd"><th>Dish Name</th><td>
<input  style="width: 350px;" maxlength="32" name="dishName" type="text" value="<?php echo $model->dishName;?>">
<tr class="even"><th>Dish url</th><td> 
<input readonly="readonly" style="width: 350px;" maxlength="32" name="url" type="text" value="<?php echo $model->url;?>">

<tr class="odd"><th>Cuisines</th><td>

		<?php  
// 		CHtml::dropDownList('post', $select, $data)
// 		if($model->checkedInRestaurantId > 0)
// 			echo '<a href="'.Yii::app()->createUrl('restaurant/'.$model->checkedInRestaurantId).'">'. $model->checkedInRestaurant->restaurantName.'</a>';
// 		else 
			$selected = array();
			foreach ($model->dishCuisine as $cuisine){
				$selected[] = $cuisine->cuisineId;	
			}
			echo CHtml::dropDownList(
					'cuisine', 
					$selected, 
					CHtml::listData(
							Cuisine::model()->findAll(
									array("condition"=>"isDisabled = 0", "select"=>"id, cuisineName")),
							'id', 
							'cuisineName'),
					array('empty'=>'Select a Cuisine','prompt'=> 'Please Select', 'multiple' => 'multiple')
					); 


?>
<script>
	$('#cuisine').selectize({
		plugins: ['remove_button'],
		allowEmptyOption: true,
		 maxItems: 10
	});

	$('.selectize-control').width('360px');
	
</script>
</td></tr>

<?php


$model->isDisabled==1 ? 'Disabled':'Yes';?>
</td></tr>

<tr class="even"><th></th><td>
<?php 
        
        $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Update',
//                 'url' => '#',
                'htmlOptions' => array('type'=>'Submit', "value"=>"Submit"),
            )
        ); ?>
&nbsp;&nbsp;
<?php if($model->isDisabled){?>
<button class="btn btn-success" onclick="confrm('restore')" >Restore</button>
<?php }else {?>
<button class="btn btn-warning" onclick="confrm('disable')" >Disable</button>
<?php }?>
</td></tr>
</tbody></table>
<input type="hidden" name="action" value="update">
</form>

<br>
<ul class="nav nav-tabs">
  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/".$model->id); ?>">All</a></li>
  <li<?php if($type=='checkin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/view/id/".$model->id."/type/checkin"); ?>">Check-in</a></li>
  <li<?php if($type=='reviews') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/view/id/".$model->id."/type/reviews"); ?>">Reviews</a></li> 
  <li<?php if($type=='images') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/view/id/".$model->id."/type/images"); ?>">Images</a></li>
  <li<?php if($type=='post') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/view/id/".$model->id."/type/post"); ?>">Posts</a></li>
  <li<?php if($type=='reported') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/view/id/".$model->id."/type/reported"); ?>">Reported</a></li>
  
</ul>
<?php 


$options = array();
$options['dish'] = $model->id;


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
								'url'=>'Yii::app()->createAbsoluteUrl("post/delete/",array("id"=>$data->id))',
								'visible'=>'!$data->isDisabled',
						),
						'restore' => array(
								'label'=> 'R',
								'url'=>'Yii::app()->createAbsoluteUrl("post/restore", array("id"=>$data->id))',
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
<script>
function confrm(action) {
   event.preventDefault();	
    var txt;
    var r = confirm("Do you want to "+action+" the Dish");
    if (r == true) {
		location.href="<?php echo Yii::app()->createUrl('dish/"+action+"/'.$model->id); ?>";
    }
}
</script>