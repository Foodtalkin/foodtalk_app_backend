<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

// $this->breadcrumbs=array(
// 	'Restaurants'=>array('admin'),
// );

// $this->menu=array(
// 	array('label'=>'Manage', 'url'=>array('restaurant/admin')),
// 	array('label'=>'Create Restaurant', 'url'=>array('restaurant/create')),
// 	array('label'=>'New Restaurant Suggestion', 'url'=>array('restaurant/suggestion')),
// 	array('label'=>'Restaurant Reported', 'url'=>array('restaurant/reported')),
// );



$this->breadcrumbs = array (
		'links' => array (
				'Restaurants'
		)
);

$this->menu = array (
		array (
				'label' => 'Manage',
				'url' => 'admin',
		),
		array (
				'label' => 'Create',
				'url' => 'create',
				'itemOptions' => array (
						'class' => 'active'
				)
		),
		// 		array (
				// 				'label' => 'Reported Restaurant',
				// 				'url' => 'reported',
				// 		)
);

?>

<h1>Create Restaurant</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>