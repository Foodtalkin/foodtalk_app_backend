<?php
use Facebook\GraphAlbum;
/* @var $this UserController */
/* @var $model User */

// $this->breadcrumbs=array(
// 	'Users'=>array('admin'),
// 	'Manage',
// );

$this->breadcrumbs=array(
		'links' => array( 'Users'),
// 		'Users'
);


$this->menu=array(
		array('label' => 'Manage','itemOptions' => array('class' => 'active' )),
		array('label' => 'Create','url' => 'create')
		,
);

$action = Yii::app()->controller->action->id;

$this->menu=array(
		array('label'=>'Active users', 'url'=>'admin','itemOptions' => array('class' => $action == 'admin' ? 'active' : '' )),
		array('label'=>'Reported users', 'url'=>'reported','itemOptions' => array('class' => $action == 'reported' ? 'active' : '' )),	
		array('label'=>'Disabled users', 'url'=>'disabled','itemOptions' => array('class' => $action == 'disabled' ? 'active' : '' )),
		
	//array('label'=>'List User', 'url'=>array('index')),
	//array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->widget(
		'booster.widgets.TbNavbar',
		array(	'fixed' => false,'fluid' => true,
				'brand' => 'Manage Users',
		)
);
?>

<?php $this->widget('booster.widgets.TbExtendedGridView', array(
		'type' => 'striped condensed',
    'id'=>'user-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id',
    	array(
    		'name'=>'userName',
    		'value'=>'isset($data->userName) ? "<a target=\"_blank\" href= \"http://foodtalk.in/$data->userName\">$data->userName<a> ":""',
    		'type'  => 'raw',
//     		'filter' => false
		),	
        'email',
    	'gender',
    	array('name'=>'city', 'value'=>'$data->cityId > 0? $data->userCity->cityName:""'),	
    		array(
//     				CHtml::link($data->fullName,array("http://www.facebook.com/".$data->facebookId))
    				
    				'name'=>'fullName',
    				'value'=>'(isset($data->facebookId))? "<a target=\"_blank\" href= \"http://www.facebook.com/$data->facebookId\">$data->fullName<a> ":""', // link version
    				// 			'filter' => CHtml::listData(Restaurant::model()->findAll(), 'id', 'restaurantName'), // fields from country table
    				'type'  => 'raw',
//     				'filter' => false
    				),
//         'fullName',
    	'createDate',
        array(
            'name' => 'image',
            'type' => 'raw',
            'value' => '(!empty($data->image))? CHtml::image(imagePath("user"). $data->image, "", array("style"=>"width:50px;height:50px;")) : " "',
            'filter' => false
        ),
        /*
        'fullName',
        'gender',
        'age',
        'country',
        'state',
        'address',
        'postcode',
        'latitude',
        'longitude',
        'phone',
        'aboutMe',
        'facebookId',
        'twitterId',
        'googleId',
        'linkedinId',
        'facebookLink',
        'twitterLink',
        'googleLink',
        'linkedinLink',
        'webAddress',
        'sendPushNotification',
        'shareOnFacebook',
        'shareOnTwitter',
        'shareOnInstagram',
        'activationCode',
        'isActivated',
        'isDisabled',
        'disableReason',
        'createDate',
        'updateDate',
        'createId',
        'updateId',
        */
        array(
   			'class'=>'booster.widgets.TbButtonColumn',
            'template' => '{view} {delete} {restore}',
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
<br><br>