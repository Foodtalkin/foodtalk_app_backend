<?php
use Facebook\GraphAlbum;
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('admin'),
	'Manage',
);

$this->menu=array(
        array('label'=>'Dashboard', 'url'=>array('/dashboard')),
		array('label'=>'Active users', 'url'=>array('user/admin')),
		array('label'=>'Disabled users', 'url'=>array('user/disabled')),
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
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'user-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id',
        'userName',
        'email',
    	'gender',
    		array(
//     				CHtml::link($data->fullName,array("http://www.facebook.com/".$data->facebookId))
    				
    				'name'=>'fullName',
    				'value'=>'(isset($data->facebookId))? "<a target=\"_blank\" href= \"http://www.facebook.com/$data->facebookId\">$data->fullName<a> ":""', // link version
    				// 			'filter' => CHtml::listData(Restaurant::model()->findAll(), 'id', 'restaurantName'), // fields from country table
    				'type'  => 'raw',
    				'filter' => false
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
            'class'=>'CButtonColumn',
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
