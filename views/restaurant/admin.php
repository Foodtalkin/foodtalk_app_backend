<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

// $this->breadcrumbs=array(
// 	'Restaurants'=>array('admin'),
// 	'Manage',
// );

// $this->menu=array(
// // 	array('label'=>'Dashboard', 'url'=>array('site')),
// 	array('label'=>'Manage', 'url'=>array('restaurant/admin')),
// 	array('label'=>'Create Restaurant', 'url'=>array('create')),
// // 	array('label'=>'New Restaurant Suggestion', 'url'=>array('restaurant/suggestion')),
// 	array('label'=>'Restaurant Reported', 'url'=>array('restaurant/reported')),
// // 	array('label'=>'Disabled Restaurant', 'url'=>array('restaurant/disabled')),
// );


$this->breadcrumbs = array (
		'links' => array (
				'Restaurants'
		)
);

$this->menu = array (
		array (
				'label' => 'Manage',
				'url' => 'admin',
				'itemOptions' => array (
						'class' => 'active'
				)

		),
		array (
				'label' => 'Create',
				'url' => 'create',
		),
// 		array (
// 				'label' => 'Reported Restaurant',
// 				'url' => 'reported',
// 		)
);

$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Manage Restaurants',
				'fixed' => false,
				'fluid' => true,
				'items' => array(
						array(
								'class' => 'booster.widgets.TbMenu',
								'type' => 'navbar',
								'items' => array(
										array('label' => 'All', 'url' => 'admin', 'active' => Yii::app()->controller->action->id!='reported'?true:false),
										array('label' => 'Reported Restaurant', 'url' => 'reported', 'active' => Yii::app()->controller->action->id=='reported'?true:false),
								)
						)
				)
		)
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#restaurant-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
$action = Yii::app()->controller->action->id;
$columns = array('id', 'restaurantName', 'address');
$type = Yii::app()->request->getParam('type',false);
if($action =='reported'){	
?>
	<ul class="nav nav-tabs">
	  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/reported"); ?>">All</a></li>
	  <li<?php if($type=='1') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/reported/type/1"); ?>">Number Missmatch</a></li>
	  <li<?php if($type=='2') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/reported/type/2"); ?>">Address Missmatch</a></li>
	  <li<?php if($type=='3') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/reported/type/3"); ?>">Shutdown</a></li>	  
	</ul>	
<?php
	$columns[]= 'reportedCount';
	
	
// 		array(            // display 'author.username' using an expression
// 			'name'=>'restaurantReportCount',
//             'header'=>'count',
// //             'value'=>'count($data->id)',
//         );
	
	if(!$type){
		$type='*';
	}
}else{

	?>
		<ul class="nav nav-tabs">
		  <li<?php if($action=='admin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/admin"); ?>">Active</a></li>
		  <li<?php if($action=='verified') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/verified"); ?>">Verified</a></li>
		  <li<?php if($action=='unverified') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/unverified"); ?>">Un-verified</a></li>
		  <li<?php if($action=='inactive') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/inactive"); ?>">Inactive</a></li>
		  <li<?php if($action=='disabled') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/disabled"); ?>">Disabled</a></li>
		  <li<?php if($action=='duplicate') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/duplicate"); ?>">Duplicate</a></li>
		  <li<?php if($action=='foodtalkSuggested') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/foodtalkSuggested"); ?>">FoodtalkSuggested</a></li>
		  	  
		</ul>	
	<?php
	
}

if(Yii::app()->controller->action->id=='suggestion'){
	
	$columns[]='phoneNo';
}else{
	
// 	$columns[]='region';
	$columns[]='area';
	$columns[]='latitude';
// 	$columns[]='suggested';
}

$columns[] = array(

		'name'=>'city',
		
// 		'value'=>'(isset($data->city->cityName))?$data->city->cityName : ""',
		
// 		'value'=>'$data->cityId > 0 ? CHtml::link($data->city->cityName, array("city/".$data->cityId)) : "N/A" ', // link version
		'value'=>'isset($data->city->cityName) ? CHtml::link($data->city->cityName, array("city/".$data->cityId)) : "n/a"', // link version
		
// 		'value'=>'(isset($data->dishReview))? CHtml::link($data->dishReview->dish->dishName,array("dish/".$data->dishReview->dishId)):""', // link version
		
		// 		'value'=>'CHtml::checkBox("cid[]",$data->suggested?true:false,array("onclick"=>"suggestedResturant(this)", "value"=>$data->id,"id"=>"cid_".$data->id))',
		'type'=>'raw',

);

$columns[] = array(
		
				'name'=>'suggested',
				'value'=>'CHtml::checkBox("cid[]",$data->suggested?true:false,array("onclick"=>"suggestedResturant(this)", "value"=>$data->id,"id"=>"cid_".$data->id))',
				'type'=>'raw',
				'htmlOptions'=>array('width'=>5),
				//'visible'=>false,
		
);



$columns[] = array(
					'class'=>'booster.widgets.TbButtonColumn',
                    'template' => '{view} {update} {delete} {restore}',
                    'buttons'=>array(
                        'delete' => array( 'visible'=>'!$data->isDisabled'),
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
                );


// array(
		/*
		'email',
		'password',
		'contactName',
		'country',
		'state',
		'latitude',
		'longitude',
		'phone1',
		'phone2',
		'highlights',
		'priceRange',
		'timing',
		'image',
		'facebookId',
		'twitterId',
		'googleId',
		'linkedinId',
		'facebookLink',
		'twitterLink',
		'googleLink',
		'linkedinLink',
		'webAddress',
		'activationCode',
		'isActivated',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
		*/

// 	);


	$this->widget('booster.widgets.TbExtendedGridView', array(
			'type' => 'striped condensed',
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->search($type),
	'filter'=>$model,
	'columns'=>$columns
)); ?>
<br><br>
<script>
function suggestedResturant(checkbox){



	baseurl = '<?php echo Yii::app()->createAbsoluteUrl("restaurant/suggested/id"); ?>/'
	
	if(checkbox.checked){

		url = baseurl + checkbox.value + '/suggested/1/';	
	}
	else{
		url = baseurl + checkbox.value + '/suggested/0/';			

	}

	$.fn.yiiGridView.update('restaurant-grid', {
        type:'POST',
        url:url,
        success:function(data) {
            $.fn.yiiGridView.update('restaurant-grid');
        }
    });		
		
}

</script>