<script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<?php
/* @var $this CityController */
/* @var $model City */

$this->breadcrumbs=array(
	'Cities'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

<h1>Add City</h1>


<input type="text" id='target'>

<iframe height="400px" width="100%" frameBorder="0" scrolling="no"
       src="<?php echo Yii::app()->createAbsoluteUrl('city/map'); ?>">
    </iframe>

<?php // $this->renderPartial('_form', array('model'=>$model)); 