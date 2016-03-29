<?php
/* @var $this ManagerController */
/* @var $model Manager */

$this->breadcrumbs=array(
	'Managers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Manager', 'url'=>array('index'), 'visible'=>Yii::app()->controller->checkAuth('manager', 'index')),
	//array('label'=>'Create Manager', 'url'=>array('create'), 'visible'=>Yii::app()->controller->checkAuth('manager', 'create')),
	//array('label'=>'View Manager', 'url'=>array('view', 'id'=>$model->id), 'visible'=>Yii::app()->controller->checkAuth('manager', 'view')),
	//array('label'=>'Manage Manager', 'url'=>array('admin'), 'visible'=>Yii::app()->controller->checkAuth('manager', 'admin')),
);
?>

<h1>Change Password <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_changePasswordForm', array('model'=>$model)); ?>