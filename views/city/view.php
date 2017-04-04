<?php
/* @var $this CityController */
/* @var $model City */

// $this->breadcrumbs=array(
// 	'Cities'=>array('admin'),
// 	$model->id,
// );

// $this->menu=array(
// // 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// // 	array('label'=>'Create City', 'url'=>array('create')),
// // 	array('label'=>'Update City', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete City', 'url'=>'#', 'visible'=>$model->isDisabled==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this city?')),
// // 	array('label'=>'Restore City', 'url'=>'#', 'visible'=>$model->isDisabled==1, 'linkOptions'=>array('submit'=>array('restore','id'=>$model->id),'confirm'=>'Are you sure you want to restore this city?')),
// 	array('label'=>'Manage City', 'url'=>array('admin')),
// );

$this->breadcrumbs = array (
		'links' => array (
				'Cities'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin'),
		// 		array('label'=>'Create Restaurant', 'url'=>'create'),
		array('label'=>'Update City', 'url'=>'update/'.$model->id ),
		array('label'=>'View City', 'url'=>$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'City : '.$model->cityName,
				'fixed' => false,
				'fluid' => true,
		)
);


?>

<h1>View City #<?php echo $model->id; ?></h1>

<?php $this->widget('booster.widgets.TbDetailView', array(
	'type' => 'striped condensed',
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
