<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs=array(
	'Adwords'=>array('index'),
	$model->title,
);

$this->menu=array(
// 	array('label'=>'List Adwords', 'url'=>array('index')),
	array('label'=>'Create Adwords', 'url'=>array('create')),
	array('label'=>'Update Adwords', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Disable Adwords', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Adwords', 'url'=>array('admin')),
);
?>

<h1>View Adwords #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'entityId',
		'title',
		array(
			'name' => 'image',
			'type' => 'raw',
			'value' => (!empty($model->image)) ? '<img alt="AddImage" src="'.imagePath('post').$model->image.'">': '',
			'filter' => false
		),
		'points',
		'paymentUrl',
		'description',
		'totalSlots',
		'bookedSlots',
		'description2',
		'expiry',
		'type',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate'
	),
)); ?>
