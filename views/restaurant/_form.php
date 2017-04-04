<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
/* @var $form CActiveForm */
?>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<link rel="stylesheet" type="text/css"
	href="http://brianreavis.github.io/selectize.js/css/selectize.bootstrap3.css" />

<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
		'type' => 'horizontal',
	'id'=>'restaurant-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">
		Fields with <span class="required">*</span> are required.
	</p>

	<?php echo $form->errorSummary($model); 

		echo $form->textFieldGroup($model, 'restaurantName',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'email',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'contactName',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		
		?>
		<div class="form-group">
		<label class="col-sm-3 control-label" for="Restaurant_webAddress">City</label>
		<div class="col-sm-2 col-sm-9">
		<?php  echo $form->dropDownList ( 
				$model, 
				'cityId',
				isset($model->cityId)?
				CHtml::listData ( 
						City::model ()->findAll ( 
								array (
									"select" => "id, CONCAT(cityName, IFNULL((select CONCAT(', ', stateName) from state s where s.id = stateId), ''), ', (',  ifnull((select countryName from country c where c.id = countryId),'') , ' - ', countryId, ')') as cityName",
									"condition" => "id = " . $model->cityId,
									'order' => 'cityName ASC' 
								)
							), 
							'id', 
							'cityName' 
				):array()
				
				, 
				array()
				); 
		
		?>
		</div>
	</div>
		
		<?php
		
		echo $form->textFieldGroup($model, 'area',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'address',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'postcode',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'latitude',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'longitude',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'phone1',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'phone2',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
						
		echo $form->textFieldGroup($model, 'priceRange',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
		echo $form->textFieldGroup($model, 'timing',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));

		echo $form->textFieldGroup($model, 'phone2',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',))); 
				
				
		echo $form->switchGroup($model, 'isActivated',
				array(
						'widgetOptions' => array(
								'options' => array(
										'onColor'=> 'success', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
										'onText'=> 'YES',
										'offText' => 'NO',
										'offColor'=> 'warning',
								),
						)
				)
				); 
		
		echo $form->switchGroup($model, 'suggested');
		echo $form->switchGroup($model, 'homeDelivery');
		echo $form->switchGroup($model, 'nonVeg');
		echo $form->switchGroup($model, 'dineIn');
		echo $form->switchGroup($model, 'outdoorSeating');
		echo $form->switchGroup($model, 'airConditioned');
		echo $form->switchGroup($model, 'fullBar');
		echo $form->switchGroup($model, 'microbrewery');
		echo $form->switchGroup($model, 'sheesha');
		echo $form->switchGroup($model, 'wifi');
		echo $form->switchGroup($model, 'liveMusic');
		echo $form->textFieldGroup($model, 'webAddress',array('wrapperHtmlOptions' => array('class' => 'col-sm-2',)));
				?>

	<script>
$( document ).ready(function() {
$('#Restaurant_cityId').width('207px');	
$('#Restaurant_cityId').selectize({
    valueField: 'id',
    labelField: 'cityName',
    searchField: 'cityName',
    create: false,
    render: {
        option: function(item, escape) {

            return '<div>' +
                '<span class="title">' +
                    '<span class="name">' + escape(item.cityName) + '</span>' +
                '</span>' +
                    '<br><span class="by">' + escape(item.stateName) +', ('+ escape(item.countryName) + ' - ' + escape(item.countryId) + ')</span>' +
            '</div>';
        }
    },
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/city/list?sessionId=GUEST&searchText=' + encodeURIComponent(query),
            type: 'GET',
            error: function() {
                callback();
            },
            success: function(res) {
                callback(res.cities);
            }
        });
    }
});
});

</script>

<div class="form-group"><label class="col-sm-3 control-label" for="Ads_longitude"></label><div class ='col-sm-2' >
<?php 
        $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => $model->isNewRecord ? 'Create' : 'Save',
                'htmlOptions' => array('type'=>'Submit', "value"=>"Submit"),
            )
        ); 
?>

</div></div>
<?php $this->endWidget(); ?>

</div>
<!-- form -->