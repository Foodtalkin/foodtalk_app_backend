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
?>

<h1>View Region</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
	),
));
?>
<br>
<h3>Cities</h3>

<form method="post">
<input name="google_place_id" type="hidden" id='google_place_id'>
<input type="submit" value="Add">
</form>

<iframe height="400px" width="100%" frameBorder="0" scrolling="no"
         src="<?php echo Yii::app()->createAbsoluteUrl('city/map'); ?>">
    </iframe>

<?php 
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'post-grid',
		'dataProvider'=>$cities->search(array('regionId'=>$model->id)),
		'columns'=>array(
			'id',
			'cityName',	
		array(
				'class'=>'CButtonColumn',
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
