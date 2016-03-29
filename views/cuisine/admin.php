<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Cuisines'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
// 	array('label'=>'Create Cuisine', 'url'=>array('create')),
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
?>

<h1>Create New</h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<h1>Manage Cuisines</h1>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cuisine-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'cuisineName',
		array(
                    'name' => 'image',
                    'type' => 'raw',
                    'value' => '(!empty($data->image))? CHtml::image(Yii::app()->baseUrl . "/images/user/thumb/" . $data->image, "", array("style"=>"width:50px;height:50px;")) : " "',
                    'filter' => false
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template' => '{view} {update} {delete}',
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
