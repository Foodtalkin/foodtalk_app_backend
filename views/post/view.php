<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />

<link rel="stylesheet" type="text/css"
	href="http://antenna.io/demo/jquery-bar-rating/dist/themes/css-stars.css" />
<script
	src="http://antenna.io/demo/jquery-bar-rating/jquery.barrating.js"></script>

<?php
$rating = array(1,2,3,4,5);
// $rating = array(0.5,1,1.5,2,2.5,3,3.5,4,4.5,5);
/* @var $this PostController */
/* @var $model Post */

// $this->breadcrumbs=array(
// 	'Posts'=>array('admin'),
// 	$model->id,
// );

$this->breadcrumbs = array (
		'links' => array (
				'Posts' => 'admin',
				$model->id
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
				'label' => 'View',
				'itemOptions' => array (
						'class' => 'active'
				)
		),
);




// $this->menu=array(
// 		array('label'=>'New Post', 'url'=>array('post/create')),
// // 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// // 	array('label'=>'Delete Post', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this post?')),
// // 	array('label'=>'Restore Post', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this post?')),
// 	array('label'=>'Manage Post', 'url'=>array('admin')),
// // 	array('label'=>'Update Post', 'url'=>array('update', 'id'=>$model->id)),		
		
// );
$dishTag = DishReview::model()->findByAttributes(array('postId'=>$model->id));

// $sql = "SELECT p.id, postId FROM post p inner join user u on p.userId = u.id LEFT join dishReview d on d.postId = p.id WHERE p.image IS NOT NULL and p.isDisabled =0 and d.postId is null and p.id != ".$model->id." ORDER BY p.id  DESC LIMIT 1";

// $nesxres = Yii::app()->db->createCommand($sql)->queryAll(true);
// foreach ($nesxres as $val){
// 	$nextpost = $val['id'];
// }


$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Post : '.$model->id,
		)
);

?>
<div class="form">
<form class="form-horizontal" id="post-form" method="post">

<div class="form-group">
		<label class="col-sm-3 control-label" >User</label>
		<div class="col-sm-3 col-sm-9">
			<?php echo '<a href="'.Yii::app()->createUrl('user/'.$model->userId).'">'. $model->user->userName.'</a>';?>
		</div>
	</div>

<div class="form-group">
		<label class="col-sm-3 control-label" >Dish Name</label>
		<div class="col-sm-3 col-sm-9">
		<input  style="width: 250px;margin-bottom: 1px" maxlength="32" name="dishName" type="text" value="
<?php 
$suggestion = false;
if($dishTag){
	echo $dishTag->dish->dishName;
}else {
	$suggestion = true;
	$tags = TagMap::model()->findAllByAttributes(array('postId'=>$model->id));
	foreach ($tags as $tag){
		echo $tag->tag->tagName.' ';
	}
}
?>">
		</div>
</div>

<div class="form-group">
		<label class="col-sm-3 control-label" >Rating</label>
		<div class="col-sm-3 col-sm-9">
		<select id="rating" name="rating">
<?php
$rate =3;
if($dishTag){
	$rate = $dishTag->rating;
}
foreach ($rating as $value){
	if($value==$rate)
		echo "<option selected value='$value'>$value</option> ";
	else		
		echo "<option value='$value'>$value</option> ";
}
?>
	</select>
		</div>
	</div>	

<div class="form-group">
		<label class="col-sm-3 control-label" >Checkedin</label>
		<div class="col-sm-3 col-sm-9">
		<input  id="checkedin" name="checkedin" type="checkbox" <?php 
if($model->checkedInRestaurantId > 0 )
echo 'checked';
?> >
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label" >Checked InRestaurant</label>
		<div class="col-sm-3 col-sm-9">
		<?php  
// 		CHtml::dropDownList('post', $select, $data)
// 		if($model->checkedInRestaurantId > 0)
// 			echo '<a href="'.Yii::app()->createUrl('restaurant/'.$model->checkedInRestaurantId).'">'. $model->checkedInRestaurant->restaurantName.'</a>';
// 		else 
			echo CHtml::dropDownList(
					'checkedInRestaurantId', 
					$model->checkedInRestaurantId, 
					CHtml::listData(
							Restaurant::model()->findAll(
									array("condition"=>"id = ".($model->checkedInRestaurantId ? $model->checkedInRestaurantId : 0),'order'=>'restaurantName', "select"=>"id, CONCAT(restaurantName, ' (', IFNULL(area, IFNULL(address, '') )  , ')', IF(isDisabled = 1, '-DISABLE RESTAURANT', IF(isActivated = 0, '-INACTIVE RESTAURANT', ''))) as restaurantName")),
							'id', 
							'restaurantName'),
					array('empty'=>'Select a Restaurant')
					); 
?><a target="_blank" href="#" >Open this Resturant</a>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label" >Image</label>
		<div class="col-sm-3 col-sm-9">
		<img src="<?php echo imagePath('post') . $model->image; ?>" alt="">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label" >Review</label>
		<div class="col-sm-3 col-sm-9">
		<textarea  name="tip" id="tip"><?php echo $model->tip; ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" >Create Date</label>
		<div class="col-sm-3 col-sm-9">
		<?php echo $model->createDate ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" >Is Disabled</label>
		<div class="col-sm-3 col-sm-9">
		<?php 
$this->widget(
		'booster.widgets.TbSwitch',
		array(
				'name' => 'isDisabled',
				'options' => array(
						'size' => 'large', //null, 'mini', 'small', 'normal', 'large
						'onColor' => 'warning', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
						'offColor' => 'success',  // 'primary', 'info', 'success', 'warning', 'danger', 'default'
						'onText'=>'Yes',
						'offText'=>'No'
						
				),
				'value'=>$model->isDisabled==1 ? true:false,

		)
);
?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" ></label>
		<div class="col-sm-3 col-sm-9">
		<button type="Submit" value="update" class="btn btn-primary" id="yw1" name="yt0">update</button>
		</div>
	</div>

<script>
$( document ).ready(function() {
	  $('#rating').barrating({
        theme: 'css-stars',
        showSelectedRating: false
    });
    
	  $( ".fadeout" ).fadeOut( "slow", function() {
	  });
});

$( document ).ready(function() {
		$('#checkedInRestaurantId').selectize({
		    valueField: 'id',
		    labelField: 'restaurantName',
		    searchField: 'restaurantName',
		    create: false,
		    render: {
		        option: function(item, escape) {

		            return '<div>' +
		                '<span class="title">' +
		                    '<span class="name">' + escape(item.restaurantName) + '</span>' +
		                '</span>' +
		                    '<br><span class="by">' + escape(item.address) +', ('+ escape(item.region) +')</span>' +
		            '</div>';
		        }
		    },
		    load: function(query, callback) {
		        if (!query.length) return callback();
		        $.ajax({
		        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/restaurant/list?sessionId=GUEST&searchText=' + encodeURIComponent(query),
		            type: 'GET',
		            error: function() {
		                callback();
		            },
		            success: function(res) {
		                callback(res.restaurants);
		            }
		        });
		    }
		});
		});
	
	$('#Post_userId').selectize({
		allowEmptyOption: false
	});

	$('#checkedInRestaurantId').width('250px');
	
// 	$('.selectize-control').width('300px');
// 	$('.selectize-control').css( "float", "left" );

	$('#checkedin').change( function() {

		if(document.getElementById('checkedin').checked) {
		    $("#restaurant").show();
		} else {
		    $("#restaurant").hide();
// 		    $('#checkedInRestaurantId').val('');
		}

	});
<?php
if(!$model->checkedInRestaurantId){
	?>
    $("#restaurant").hide();
	<?php
}
?>
</script>
<input type="hidden" name="action" value="update">
</form>
</div>