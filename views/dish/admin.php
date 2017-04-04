<?php
/* @var $this CuisineController */
/* @var $model Cuisine */


$this->breadcrumbs = array (
		'links' => array (
				'Dishes'
		)
);


$this->menu=array(
// 	array('label'=>'Create Dish', 'url'=>array('create')),
	array('label'=>'Manage', 'url'=>'admin', 'itemOptions' => array ('class' => 'active')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cuisine-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$type = Yii::app()->request->getParam('type',false);

$this->widget(
		'booster.widgets.TbNavbar',
		array(
				'brand' => 'Manage Dishes',
				'fixed' => false,
				'fluid' => true,
		)
);

?>

<br>
<ul class="nav nav-tabs">
  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/admin"); ?>">All</a></li>
  <li<?php if($type=='active') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/admin/type/active"); ?>">Active</a></li>
  <li<?php if($type=='disabled') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/admin/type/disabled"); ?>">Disabled</a></li>

  
</ul>

<?php 
	$this->widget('booster.widgets.TbExtendedGridView', array(
	'type' => 'striped condensed',
	'id'=>'cuisine-grid',
	'dataProvider'=>$model->search($type),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'postCount',
			'filter' => false
		),			
		'dishName',
		'createDate',
		'updateDate',
                array(
				'class'=>'booster.widgets.TbButtonColumn',
                    'template' => '{view} {delete}',
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
