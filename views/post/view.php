
<script src="/themes/abound/js/selectize.js"></script>
<script src="/foodtalk/themes/abound/js/selectize.js"></script>
<?php
$rating = array(1,2,3,4,5);
// $rating = array(0.5,1,1.5,2,2.5,3,3.5,4,4.5,5);
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs=array(
	'Posts'=>array('admin'),
	$model->id,
);

$this->menu=array(
		array('label'=>'New Post', 'url'=>array('post/create')),
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Delete Post', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this post?')),
// 	array('label'=>'Restore Post', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this post?')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
// 	array('label'=>'Update Post', 'url'=>array('update', 'id'=>$model->id)),		
		
);
$dishTag = DishReview::model()->findByAttributes(array('postId'=>$model->id));

$sql = "SELECT p.id, postId FROM post p inner join user u on p.userId = u.id LEFT join dishReview d on d.postId = p.id WHERE p.image IS NOT NULL and p.isDisabled =0 and d.postId is null and p.id != ".$model->id." ORDER BY p.id  DESC LIMIT 1";

$nesxres = Yii::app()->db->createCommand($sql)->queryAll(true);
foreach ($nesxres as $val){
	$nextpost = $val['id'];
}
if(isset($nextpost)){
?>
<div style="border:10px; padding-right:300px; float: right;"><a href="<?php echo Yii::app()->createUrl('post/'.$nextpost); ?>" class="likeabutton">NEXT > </a></div>
<?php }?>
<h1>Post Id: <?php echo $model->id; ?></h1>
<form method="post">
<table class="general" id="yw0"><tbody>
<tr class="even"><th>User</th><td>
<?php echo '<a href="'.Yii::app()->createUrl('user/'.$model->userId).'">'. $model->user->userName.'</a>';?>
</td></tr>
<tr class="odd"><th>Dish Name</th><td>
<input  style="width: 250px;" maxlength="32" name="dishName" type="text" value="
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
<?php if($suggestion){?>
&nbsp;&nbsp;<span class='errorMessage'>Suggested</span>
<?php }?>
</td></tr>
<tr class="even"><th>Rating</th>
<td>
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
</td></tr>

<tr>
<tr class="odd"><th>Checkedin</th>
<td>
<input type="checkbox" >
</td>
</tr>
<tr class="odd"><th>Checked InRestaurant</th><td>

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
									array("condition"=>"(isActivated = 1 and  isDisabled = 0) or id = ".intval($model->checkedInRestaurantId),'order'=>'restaurantName', "select"=>"id, CONCAT(restaurantName, ' (', IFNULL(area, IFNULL(address, '') )  , ')', IF(isDisabled = 1, '-DISABLE RESTAURANT', IF(isActivated = 0, '-INACTIVE RESTAURANT', ''))) as restaurantName")),
							'id', 
							'restaurantName'),
					array('empty'=>'Select a Restaurant')
					); 


?>
<a target="_blank" href="#" >Open this Resturant</a>

<script>
	$('#checkedInRestaurantId').selectize({
		allowEmptyOption: true
	});
	$('#Post_userId').selectize({
		allowEmptyOption: false
	});

	$('.selectize-control').width('300px');
	$('.selectize-control').css( "float", "left" );
	
</script>
</td></tr>
<tr class="even"><th>Image</th><td><img src="<?php echo imagePath('post') . $model->image; ?>" alt=""></td></tr>
<tr class="odd"><th>Review</th><td><textarea size="80" maxlength="200" style="width: 450px; height:100px" name="tip" id="tip"><?php echo $model->tip; ?></textarea></td></tr>
<tr class="even"><th>Create Date</th><td>2015-10-07 16:44:50</td></tr>
<tr class="odd"><th>Active</th><td>
<select name="isDisabled">
<option <?php echo $model->isDisabled==1 ? '':'selected';?> value="0">Yes</option>
<option <?php echo $model->isDisabled==1 ? 'selected':'';?> value="1">Disable</option>
</select>
</td></tr>
<tr class="even"><th></th><td><input value="update" type="submit"></td></tr>
</tbody></table>
<input type="hidden" name="action" value="update">
</form>
