<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Cuisines'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
	array('label'=>'Create Cuisine', 'url'=>array('create')),
// 	array('label'=>'Update Cuisine', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete Cuisine', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this cuisine?')),
	array('label'=>'Restore Cuisine', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this cuisine?')),
	array('label'=>'Manage Cuisine', 'url'=>array('admin')),
);
?>

<h1>Cuisine: <?php echo $model->cuisineName; ?></h1>

<form method="post" name="cuisineName" onsubmit="return validate_frm(this)">

<!-- <table class="detail-view" id="yw0"><tbody><tr class="odd"><th>ID</th><td>19</td></tr> -->
<!-- <tr class="even"><th>Cuisine Name</th><td> Chinese</td></tr> -->
<!-- </tbody></table> -->
<!-- <input type="radio" name="status" id="duplicate" value="duplicate"> Duplicate with -->
<?php 
// echo CHtml::dropDownList(
// 						'duplicateId',
// 						$model->id, 
// 						CHtml::listData(
// 								Cuisine::model()->findAll(
// 										array("condition"=>"isDisabled = 0 and id != ".$model->id,'order'=>'cuisineName', "select"=>"id, cuisineName")), 
// 								'id', 
// 								'cuisineName'
// 								),
// 						array('empty'=>array('Select a Cuisine'))
// 					);
?>

</form>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'cuisineName',
// 		array('type'=>'image','value'=>Yii::app()->baseUrl . '/images/restaurant/thumb/' . $model->image, 'label'=>'Image'),
	),
		
		
		
));


		$model1 = new Dish('search');

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'cuisine-grid',
		'dataProvider'=> $model1->search(),
		'filter'=> $model1,
		'columns'=>array(
				array(
					'name'=>'dishName',				
					'value'=>'CHtml::link($data->dishName,array("dish/".$data->id))', // link version
					'type'  => 'raw',
					'filter' => false	
				),
				'createDate',
				'updateDate',
				array(
						'class'=>'CButtonColumn',
						'template' => '{delete}',
						'buttons'=>array(
// 								'delete' => array(
// 										'visible'=>'!$data->isDisabled',
// 								),
								'delete' => array(

										'url'=>'Yii::app()->createAbsoluteUrl("cuisine/removedish", array("dishId"=>$data->id, "cuisineId"=>'.$model->id.'))',
// 										'visible'=>'$data->isDisabled',
										'click'=>"function(){
                                if(!confirm('Are you sure to restore this item?')) return false;
                                $.fn.yiiGridView.update('cuisine-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                        $.fn.yiiGridView.update('cuisine-grid');
                                    }
                                })
                                return false;
                            }",
// 										//'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
// 										//'options' => array('class'=>'copy'), // HTML options for the button
								),
				),
		),

),
));
?>
