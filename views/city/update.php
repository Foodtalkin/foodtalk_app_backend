<?php
/* @var $this CityController */
/* @var $model City */

// $this->breadcrumbs=array(
// 	'Cities'=>array('admin'),
// 	$model->id=>array('view','id'=>$model->id),
// 	'Update',
// );

// $this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Create City', 'url'=>array('create')),
// 	array('label'=>'View City', 'url'=>array('view', 'id'=>$model->id)),
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
		array('label'=>'Update City', 'url'=>'update/'.$model->id ,'itemOptions' => array ('class' => 'active')),
		array('label'=>'View City', 'url'=>$model->id),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Update : '.$model->cityName,
				'fixed' => false,
				'fluid' => true,
		)
);

?>

<h1>Update City <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>