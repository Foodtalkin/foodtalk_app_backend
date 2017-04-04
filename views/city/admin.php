<?php
/* @var $this CityController */
/* @var $model City */

$this->breadcrumbs = array (
		'links' => array (
				'Cities'
		)
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
		array('label'=>'Manage', 'url'=>'admin','itemOptions' => array ('class' => 'active')),
// 		array('label'=>'Create Restaurant', 'url'=>'create'),
// 		array('label'=>'update Restaurant', 'url'=>'update/'.$model->id ),
// 		array('label'=>'View Restaurant', 'url'=>'view/'.$model->id,'itemOptions' => array ('class' => 'active')),
);


$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Add City',
				'fixed' => false,
				'fluid' => true,
		)
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

<form method="post">
<input name="google_place_id" type="hidden" id='google_place_id'>
<button type="Submit" value="Submit" class="btn btn-primary" id="yw12" name="yt0">Add</button>
</form>

<iframe height="400px" width="100%" frameBorder="0" scrolling="no"
        src="<?php echo Yii::app()->createAbsoluteUrl('city/map'); ?>">
    </iframe>

<br><br>
<?php 
$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Cities',
				'fixed' => false,
				'fluid' => true,
		)
);
?>
<?php $this->widget('booster.widgets.TbExtendedGridView', array(
	'type' => 'striped condensed',
	'id'=>'city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'cityName',
		'state.stateName',
		'countryId',
		'country.countryName',
		array('name'=>'resturantCount', 'filter'=>false),	
		array('name'=>'userCount','filter'=>false),			
		array(
                    'class'=>'booster.widgets.TbButtonColumn',
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
