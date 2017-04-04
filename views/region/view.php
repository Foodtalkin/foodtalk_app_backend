<?php
/* @var $this FlagController */
/* @var $model Flag */

$this->breadcrumbs=array(
	'region'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'New Region', 'url'=>array('create')),
// 	array('label'=>'Update Flag', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Region', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage', 'url'=>array('admin')),
);


$this->breadcrumbs = array (
		'links' => array (
				'Regions'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin'),
		array('label'=>'Add Region', 'url'=>'create'),
// 		array('label'=>'Delete Region', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
		array('label'=>'View Region', 'url'=>$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Region : '.$model->name,
				'fixed' => false,
				'fluid' => true,
		)
);
?>
<?php $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'createDate',
		'updateDate',
// 		'createId',
// 		'updateId',
	),
));
?>
<br><br>
<?php 

$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Add a city to '.$model->name,
				'fixed' => false,
				'fluid' => true,
		)
);
?>

<form method="post">
<input name="google_place_id" type="hidden" id='google_place_id'>
<button type="Submit" value="Add" class="btn btn-primary" id="yw12" name="yt0">Add </button>
</form>

<iframe height="400px" width="100%" frameBorder="0" scrolling="no"
         src="<?php echo Yii::app()->createAbsoluteUrl('city/map'); ?>">
    </iframe>
<br><br>
<?php 

$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Cities of '.$model->name,
				'fixed' => false,
				'fluid' => true,
		)
);
 $this->widget('booster.widgets.TbExtendedGridView', array(
	'type' => 'striped condensed',
		'id'=>'post-grid',
		'dataProvider'=>$cities->search(array('regionId'=>$model->id)),
		'columns'=>array(
			'id',
			'cityName',	
		array(
				'class'=>'booster.widgets.TbButtonColumn',
// 				'class'=>'CButtonColumn',
				'template' => '{view} {delete}',
				'buttons'=>array(
						'view' => array(
								'url'=>'Yii::app()->createAbsoluteUrl("city/".$data->id)',
						),
						'delete' => array(
								'visible'=>'!$data->isDisabled',
								'url'=>'Yii::app()->createAbsoluteUrl("city/removeregion/id/".$data->id)',
						),

		),
),
),
));
?>
<br><br>