<?php
/* @var $this AdwordsController */
/* @var $model Adwords */


$this->breadcrumbs=array(
		'links' => array(
				'Adwords' => 'admin',
// 				'create'=> 'create',
				'Update'
		));

$this->menu=array(
		array('label' => 'Manage', 'url' => 'admin'),
		array('label' => 'Create','url' => 'create'),
		array('label' => 'Edit','itemOptions' => array('class' => 'active' )),
		array('label' => 'View','url' => 'view/'.$model->id)
);

// $this->menu=array(
// // 	array('label'=>'List Adwords', 'url'=>array('index')),
// 	array('label'=>'Create Adwords', 'url'=>array('create')),
// 	array('label'=>'View Adwords', 'url'=>array('view', 'id'=>$model->id)),
// 	array('label'=>'Manage Adwords', 'url'=>array('admin')),
// );
?>

<h1>Update Adwords <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>