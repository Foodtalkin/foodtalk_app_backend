<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />

<?php

$this->breadcrumbs = array (
		'links' => array (
				'Restaurant'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage Restaurant', 'url'=>'admin'),
		array('label'=>'Create Restaurant', 'url'=>'create'),
		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
		array('label'=>'View Restaurant', 'url'=>'view/'.$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Restaurant : '.$model->restaurantName,
				'fixed' => false,
				'fluid' => true,
		)
);


/* @var $this RestaurantController */
/* @var $model Restaurant */

// $this->breadcrumbs=array(
// 	'Restaurants'=>array('admin'),
// 	$model->id,
// );

// $this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Create Restaurant', 'url'=>array('create')),
// 	array('label'=>'Update Restaurant', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete Restaurant', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this restaurant?')),
// 	array('label'=>'Restore Restaurant', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this restaurant?')),
// 	array('label'=>'Manage Restaurant', 'url'=>array('admin')),
// );

$status = array(
		'active' => '',
		'inactive' => '',
		'verified' => '',
		'unverified' => '',
		'disabled' => '',
		'duplicate' => ''		
);


if($model->duplicateId){
	$status['duplicate'] = 'checked';
}
else{

	
	if($model->isDisabled){
		$status['disabled'] = 'checked';
	}else if($model->isActivated){
		$status['active'] = 'checked';
	}else 
		$status['inactive'] = 'checked';
	

	
	if($model->verified){
		$status['verified'] = 'checked';
	}else{
		$status['unverified'] = 'checked';
	}
}
?>

<script>
$( document ).ready(function() {
$('#duplicateId').selectize({
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
        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/restaurant/list?exceptions=<?php echo $model->id; ?>&sessionId=GUEST&searchText=' + encodeURIComponent(query),
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
</script>

<form method="post" name="restutantStatus" onsubmit="return validate_frm(this)" class="form-horizontal">

	<div class="form-group">
		<label class="col-sm-1 control-label" for="TestForm_inlineCheckboxes">&nbsp;</label>
		<div class="col-sm-9">
				<span id="TestForm_inlineCheckboxes">
					<label class="checkbox-inline">
						<input class="" type="radio" name="status" <?php echo $status['active']; ?> value="active">Active &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</label>
					
					<label class="checkbox-inline">
<input type="radio" name="status" <?php echo $status['inactive']; ?> value="inactive">  Inactive 
					</label>

					<label class="checkbox-inline">
<input type="radio" name="status" <?php echo $status['disabled']; ?> value="disabled">Disabled					</label>

					
				</span>
		</div>
	</div>
		
	<div class="form-group">
			<label class="col-sm-1 control-label" for="TestForm_inlineCheckboxes">&nbsp;</label>
		<div class="col-sm-9">
			<span id="TestForm_inlineCheckboxes">
			<label class="checkbox-inline">
				<input type="radio" name="status" <?php echo $status['duplicate']; ?> id="duplicate" value="duplicate"> Duplicate with
			</label>
		</span>
</div></div>
	<div class="form-group">
			<label class="col-sm-1 control-label" for="TestForm_inlineCheckboxes">&nbsp;</label>
		<div class="col-sm-3 col-sm-9">
<?php echo CHtml::dropDownList(
						'duplicateId',
						$model->duplicateId, 
						CHtml::listData(
								Restaurant::model()->findAll(
										array("condition"=>"id = ".($model->duplicateId ? $model->duplicateId : 0),'order'=>'restaurantName', "select"=>"id, CONCAT(restaurantName, ' (', IFNULL(area, IFNULL(address, '') )  , ')', IF(isDisabled = 1, '-DISABLE RESTAURANT', IF(isActivated = 0, '-INACTIVE RESTAURANT', ''))) as restaurantName")), 
								'id', 
								'restaurantName'
								)
// 						array('empty'=>array('Select a Restaurant'))
					);
?>
					<?php 
if($model->duplicateId)
	$display = '';
else
	$display = 'style="display: none;"';
?>
<a target="_blank" href="<?php echo $model->duplicateId; ?>"   id="duplaicateResturant" <?php echo $display;?> >Open Resturant</a>
				
		</div>
	</div>

<script>
// 	$('#duplicateId').selectize({
// 		allowEmptyOption: true
// 	});

	$('.selectize-control').width('400px');
	$('#duplicateId').width('400px');
// 	$('.selectize-control').style["padding-left"] = "60px";
	
</script>

<div class="form-group"><label class="col-sm-1 control-label" for="Ads_longitude"></label><div class="col-sm-2">
<input type="hidden" name="action" value="update">
<button type="Submit" value="Submit" class="btn btn-primary" id="yw12" name="yt0">Update</button>
<a class="btn btn-primary" href="update/<?php echo $model->id; ?>">Edit</a>
</div></div>

</form>
<script type="text/javascript">

$( document ).ready(function() {
	$('#duplicateId').change(function (){
		$('#duplicate').prop("checked", true);
		$('#duplaicateResturant').prop("href", $("#duplicateId").val());
		$('#duplaicateResturant').show();
	});
});

function validate_frm(form){
// 	event.preventDefault();
	if($('#duplicate').prop("checked")){
		if($("#duplicateId").val()==0){
			alert('Please select a duplicate resturant');
			return false;
		}
		
		var r = confirm("Are you sure! Changes made can't be undone.");
		if (r == true) {
			return true;
		} else {
			return false;
		}
// 		alert("Are you sure! Changes made can't be undone.");
// 		console.log($("#duplicateId").val());
	}
// 	alert('TEST')
// 	return false;
}

//-->
</script>

<?php $this->widget('booster.widgets.TbDetailView', array(
		'type' => 'striped condensed',
		'data'=>$model,
		'attributes'=>array(
		'id',
		'restaurantName',
		'email',
		'contactName',
		array(
				'name' => 'city',
				'type' => 'raw',
				'value' => (isset($model->city->cityName))? $model->city->cityName :"" 
		),
		array(
				'name' => 'country',
				'type' => 'raw',
				'value' => (isset($model->city->cityName))? $model->city->country->countryName :""
		),
		array(
				'name' => 'state',
				'type' => 'raw',
				'value' => (isset($model->city->stateId))? $model->city->state->stateName :""
		),			
		array(
				'name' => 'region',
				'type' => 'raw',
				'value' => (isset($model->city->regionId))? $model->city->region->name :""
		),
// 		'city',
// 		'region',
		'area',
		'address',
		'postcode',
		'latitude',
		'longitude',
		'phone1',
		'phone2',
			array('type'=>'image',
					'value'=> $model->homeDelivery ? 
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Home Delivery'),
// 			'homeDelivery',
			array('type'=>'image',
					'value'=> $model->nonVeg ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Non Veg'),
			array('type'=>'image',
					'value'=> $model->dineIn ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Dine In'),
			array('type'=>'image',
					'value'=> $model->outdoorSeating ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Outdoor Seating'),
			array('type'=>'image',
					'value'=> $model->airConditioned ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Air Conditioned'),
			array('type'=>'image',
					'value'=> $model->fullBar ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Bar Avilable'),
			array('type'=>'image',
					'value'=> $model->microbrewery ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Microbrewery'),
			array('type'=>'image',
					'value'=> $model->sheesha ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Sheesha'),						
			array('type'=>'image',
					'value'=> $model->wifi ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'Wifi'),
			array('type'=>'image',
					'value'=> $model->liveMusic ?
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAC/0lEQVQ4T62UW0hUQRjHv5mzu+q6XkskkzCJvGyxZYm+ZBZGlxcJzYdCbBWEjNawoLKCDcqX8qViRQkzkggMIaReCiqkgjIq3U2ljCyvyXppd3XPnjMzcUb2rOutfWieznzn+37z/y4zCP7zQqvxjA6rTuuZMlEkbECYMgrCoI65uj/sbJJWilsWuKPr9DoZoVoqw3EkgGFhMGPwByG4C1qhrtt04/di8BLg1vc1hxCQh4Bw1GrqGdAZxjRH7Nn1zxb6BQEVGADtQAjhUEpLGRAAfHAhVAUqaYqMDWCEI0KBqT4E3IJWTv2YdWtCsalA0ztLK8PCsVBgiZoYGJdnVFdGSHNPzs0KFah0E9zTbkFA2n8BzfF7oSg2F86PtoJ97ue8O2Fi2uBwZFtJG+EKt78+mUfCdK9CgZXG53G3794xqBxqBAqM7ymRsu05t7s4cEun5SKOEK76gQLCkKxdA4M+Xha+FGV+2Kh3Eix2GzhjZLVodE48a99lq58Hvqi6gaPCzijfGiTA5cRiyNKnwrmR+/DFOxQEG/E6odreAM4YCQAFhoR4xGuO3bZL3JL5/ESNJja8nqcfngLX15cBRgg8VIROTy8ciNrGVSowRdkkVxY8wtKUp6p3X1MDtxrby004OfoTwgioT4YClAG16Uc51L9UWLQyesHVZoQB/JrO6Clu6ZuPsFqxMX9sRjBE8GtGRRkKcAA6MucEi8MGk8vAeJNd4owj3xYHCJgqYfMTc114QswFwPMmP9Scsh+qHQ0rwhihII25rH2FzVeUuMBN6ajUewT2VZdgSPJbFSh2yQDx4UvS5KcyBvKEZ3B2XEr/YW7xBgF5cx6VGale+1a31hDlVxpcrcBOUeZzuqdBYrn9hc39/j9LXpu0x+VpCEg7jtZnaiLDYDFYaQCdFUGaEj9TRIq+Hb43sPDQ5d/DxkqtO8FXihitAJ2QizQa3lciEYJk8oYB3EmK2/jg5R6rvDiDVV9sxXnT01NhgjydxIfe6x12lLT5ViqDYv8LoBE1JGs3auQAAAAASUVORK5CYII='
					:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADWklEQVQ4T62Uf0xbVRTHv/e+H21pS6Fz2WBzfyxLVIgQbCRs3VQYymD+YWL4R2OiWfxDlGVRjG7wx0w2kmVggi6yLf7YH4vRkGzJ2CCTQSCEYmIxyCSZmhlBzQwItIXCe23vvebdUtJSIPvD99d755z7ueec73mH4H9+yFa8yfpi3Wt4SgWUPeBcAGSq4J+VCTI2Ft/s3IbA2ZpDBQkNp5hgryuEutIPCyEiIOQrqvHWndcDM+vBWcAHL/rrBCffEAr3VtkLgbCiiPodN0b60uMygBaMc3RTSujDtJZzzlSV1KZD14BWmXGF3yeUOCTMYioUSCQy2ETVIFgCEELaueBLlCt7C3qHZ+WxVPSfR/1XVUJeTcHom40grjywT1uB+KoGug56vBkIzYF/cQEQXIYnBP/y0VuBY2tAS8285fwlSqBZRkEozDfehrvqCNh4EPyTs/IgPdECpcSHxf4e2K50gqwCueBmYc4uJ+nqYjLD3+sOPOOgdCi9tnkzBvtbTfBU1YDf/VEWQ58sQ6i/F2bnx/Da9YxWxKl4es+NkaAE/vpCRbNbV8+sF0JC3/kAnmerpSs0cBvGZ23Ytg5m+aKm2bSv74d2CZw8XNbmdTjfy1JWVcEa3oe9/KB0GaPDUC6eBxjLCl2IGWeLvgu2SOB4Zdm7O5zO9owoVYXS+CGobz9Cd3pgqet57nnwYADswrks9WcWVxpKh8Y6JXD4YHHpXk/eOCWrolMK5UQz6FMVWOi7CfNSh5wHR6qnY6NgHa0AT6rMhMD0fOSJA6N370mCAOi96vJwnl2Xv5k1YdGXXgE8HsQud8BrSwowt2LC3tAE/u8MXN3frs3cvGGEi+4E84k1IKkyvz9U0rrb7T6p0KQpYsaQEMhSc84woVGCXD15CeMCU6HF0/7AxEdrc2i9BH2+HO6K/7bbnVu45QpKb7QApiORqWUl8njl4B9GBtD6GPAXFbtVbbTA6XKnMs2Sc9VgZfZXOBJaBqmoGpn4JRWXlcyAv+QxjbNr23IcRbk2G9aDLQHChonZqPETo3j5cODn++mXblhd0OfTorr5mmDsmK6pFbpqbQkgHkswAyJAgc+5bfvXlYODmZsjfTlsVlpP7T6bcy6n0PI/EmV/F09OxjaLtez/AV06RCSX0SyLAAAAAElFTkSuQmCC',
					'label'=>'LiveMusic'),
		'priceRange',
		'timing',
		array('type'=>'image','value'=>$model->image ? Yii::app()->baseUrl . '/images/restaurant/thumb/' . $model->image :'', 'label'=>'Image'),
		'webAddress',
	),
)); 


$type = Yii::app()->request->getParam('type',false);

?>
<br>
<ul class="nav nav-tabs">
  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/".$model->id); ?>">All</a></li>
  <li<?php if($type=='checkin') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/view/id/".$model->id."/type/checkin"); ?>">Check-in</a></li>
  <li<?php if($type=='reviews') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/view/id/".$model->id."/type/reviews"); ?>">Reviews</a></li> 
  <li<?php if($type=='images') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/view/id/".$model->id."/type/images"); ?>">Images</a></li> 
  <li<?php if($type=='post') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/view/id/".$model->id."/type/post"); ?>">Posts</a></li>  
  <li<?php if($type=='reported') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/view/id/".$model->id."/type/reported"); ?>">Reported</a></li>
  
</ul>
<?php 

$options = array();
$options['restaurant'] = $model->id;

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
				// 		'restaurantName',
				array(
						'name'=>'userId',
						'value'=>'(isset($data->user))? CHtml::link($data->user->userName,array("user/".$data->userId)):""', // link version
						'type'  => 'raw',
						'filter' => false
				),
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
						),
		),
),
),
));

?>
