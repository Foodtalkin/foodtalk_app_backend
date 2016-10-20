<?php
/* @var $this CityController */
/* @var $model City */

$this->breadcrumbs=array(
	'Cities'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Create City', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#city-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Add City</h1>

<form method="post">
<input name="google_place_id" type="hidden" id='google_place_id'>
<input type="submit" value="Add">
</form>

<iframe height="400px" width="100%" frameBorder="0" scrolling="no"
        src="<?php echo Yii::app()->createAbsoluteUrl('city/map'); ?>">
    </iframe>


<h1>Manage Cities</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
// 		'id',
		'cityName',
		'state.stateName',
		'countryId',
		'country.countryName',
		'region.name',
		'resturantCount',
		'userCount',	
		array(
                    'class'=>'CButtonColumn',
                    'template' => '{view} {update} {delete} {restore}',
                    'buttons'=>array(
                        'delete' => array(
                            'visible'=>'!$data->isDisabled',
                        ),
                        'restore' => array(
                            'label'=> 'R',
                            'url'=>'Yii::app()->createAbsoluteUrl("item/restore", array("id"=>$data->id))',
                            'visible'=>'$data->isDisabled',
                            'click'=>"function(){
                                if(!confirm('Are you sure to restore this item?')) return false;
                                $.fn.yiiGridView.update('item-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                        $.fn.yiiGridView.update('item-grid');
                                    }
                                })
                                return false;
                            }",
                            //'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
                            //'options' => array('class'=>'copy'), // HTML options for the button
                        ),
                    ),
                ),
	),
)); ?>
