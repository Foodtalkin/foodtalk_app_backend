<?php
/* @var $this AdwordsController */
/* @var $model Adwords */

$this->breadcrumbs=array(
					'links' => array(
						'Adwords' => 'admin', 
						'create'
				));




$this->menu=
array(
		array('label' => 'Manage', 'url' => 'admin'),
// 		array(),
		array(
				'label' => 'Create',
				'url' => 'create',
				'itemOptions' => array('class' => 'active')
		),
// 		array('label' => 'Applications', 'url' => '#'),
// 		array(
// 				'label' => 'Another list header',
// 				'itemOptions' => array('class' => 'nav-header')
// 		),
// 		array('label' => 'Profile', 'url' => '#'),
// 		array('label' => 'Settings', 'url' => '#'),
// 		'',
// 		array('label' => 'Help', 'url' => '#'),
);

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'New Ad',
		)
);

$this->renderPartial('_form', array('model'=>$model)); ?>