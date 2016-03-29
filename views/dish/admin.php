<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Manage',
);

$this->menu=array(
// 	array('label'=>'Create Dish', 'url'=>array('create')),
	array('label'=>'Manage', 'url'=>array('dish/admin')),
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
?>

<h1>Manage Dishes</h1>

<br>
<ul class="nav nav-tabs">
  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/admin"); ?>">All</a></li>
  <li<?php if($type=='active') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/admin/type/active"); ?>">Active</a></li>
  <li<?php if($type=='disabled') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("dish/admin/type/disabled"); ?>">Disabled</a></li>

  
</ul>

<?php $this->widget('zii.widgets.grid.CGridView', array(
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
                    'class'=>'CButtonColumn',
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
