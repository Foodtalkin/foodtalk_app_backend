<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->breadcrumbs=array(
	'Restaurants'=>array('admin'),
	'Manage',
);

$this->menu=array(
// 	array('label'=>'Dashboard', 'url'=>array('site')),
	array('label'=>'Manage', 'url'=>array('restaurant/admin')),
	array('label'=>'Create Restaurant', 'url'=>array('create')),
	array('label'=>'New Restaurant Suggestion', 'url'=>array('restaurant/suggestion')),
	array('label'=>'Restaurant Reported', 'url'=>array('restaurant/reported')),
	array('label'=>'Disabled Restaurant', 'url'=>array('restaurant/disabled')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#restaurant-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Restaurants</h1>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<?php
$action = Yii::app()->controller->action->id;
$columns = array('id', 'restaurantName', 'address');
$type = Yii::app()->request->getParam('type',false);

?>
	<ul class="nav nav-tabs">
	  <li<?php if(!$type) { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/suggestion"); ?>">All</a></li>
	  <li<?php if($type=='pending') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/suggestion/type/pending"); ?>">Pending</a></li>
	  <li<?php if($type=='done') { ?> class="active"<?php } ?>><a href="<?php echo Yii::app()->createAbsoluteUrl("restaurant/suggestion/type/done"); ?>">Added</a></li>
	</ul>	
<?php

if(Yii::app()->controller->action->id=='suggestion'){
	
	$columns[]='phoneNo';
}else{
	
	$columns[]='city';
}

$columns[] = array(
                    'class'=>'CButtonColumn',
                    'template' => '{done}',
                    'buttons'=>array(
                        'delete' => array( 'visible'=>'!$data->isDisabled'),
                        'done' => array(
//                             'label'=> 'Done',
                        		'imageUrl'=>'http://wcdn3.dataknet.com/static/resources/icons/set29/3e4058cd.png',
                        		
                            'url'=>'Yii::app()->createAbsoluteUrl("restaurant/suggestion", array("action"=>"done","id"=>$data->id))',
							'visible'=>'!$data->isDisabled',
                            'click'=>"function(){
                                if(!confirm('Done adding?')) return false;
                                $.fn.yiiGridView.update('restaurant-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                        $.fn.yiiGridView.update('restaurant-grid');
                                    }
                                })
                                return false;
                            }",
                            //'imageUrl'=>'/path/to/copy.gif',  // image URL of the button. If not set or false, a text link is used
                            //'options' => array('class'=>'copy'), // HTML options for the button
                        ),
                    ),
                );


// array(
		/*
		'email',
		'password',
		'contactName',
		'country',
		'state',
		'latitude',
		'longitude',
		'phone1',
		'phone2',
		'highlights',
		'priceRange',
		'timing',
		'image',
		'facebookId',
		'twitterId',
		'googleId',
		'linkedinId',
		'facebookLink',
		'twitterLink',
		'googleLink',
		'linkedinLink',
		'webAddress',
		'activationCode',
		'isActivated',
		'isDisabled',
		'disableReason',
		'createDate',
		'updateDate',
		'createId',
		'updateId',
		*/

// 	);


	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->search($type),
	'filter'=>$model,
	'columns'=>$columns
)); ?>
