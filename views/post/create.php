<?php
/* @var $this PostController */
/* @var $model Post */

// $this->breadcrumbs=array(
// 	'Posts'=>array('admin'),
// 	'Create',
// );

$this->breadcrumbs = array (

		'links' => array (
				'Posts' => 'admin',
				'create'
		)
);

// $this->menu=array(
// 	array('label'=>'New Post', 'url'=>array('post/create')),	
// // 	array('label'=>'List Post', 'url'=>array('index')),
// 	array('label'=>'Manage Post', 'url'=>array('admin')),
// );

$this->menu = array (
		array (
				'label' => 'Manage',
				'url' => 'admin'
		),
		array (
				'label' => 'Create',
				'itemOptions' => array (
						'class' => 'active'
				)
		),
);

$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Create Posts',
		)
);

?>
<?php $this->renderPartial('_new', array('model'=>$model)); ?>