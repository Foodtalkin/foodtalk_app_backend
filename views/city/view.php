<?php
/* @var $this CityController */
/* @var $model City */

$this->breadcrumbs=array(
	'Cities'=>array('admin'),
	$model->id,
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Create City', 'url'=>array('create')),
// 	array('label'=>'Update City', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete City', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this city?')),
// 	array('label'=>'Restore City', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this city?')),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

<h1>View City #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'cityName',
// 		'shortName',
		'stateId',
		'state.stateName',
		'countryId',
		'country.countryName',	
		'regionId',
		'resturantCount',			
		'userCount'
	),
)); ?>
