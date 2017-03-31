<?php
/* @var $this AdwordsController */
/* @var $model Adwords */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'adwords-form',
		'type' => 'horizontal',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
		<?php echo $form->errorSummary($model); ?>
		<?php echo $form->dropDownListGroup($model, 'adType', array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(AdType::model()->findAll('isDisabled = 0'), 'id', 'name'),
					'htmlOptions' => array(),
				)
			)); ?>
		<?php echo $form->textFieldGroup($model, 'entityId',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textFieldGroup($model,'title',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		
		<?php echo $form->textFieldGroup($model,'actionButtonText',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		
		<?php echo $form->textAreaGroup($model,'description',array('wrapperHtmlOptions' => array('class' => 'col-sm-3'), 'widgetOptions' => array('htmlOptions' => array('rows' => 3),))); ?>
		
		<?php echo $form->datePickerGroup($model,'startDate',
				array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'startDate' => '-0d',
						'format'=>'yyyy-mm-dd'	
// 						'endDate'=> '+0d',
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>		
		<?php echo $form->datePickerGroup($model,'expiry',
				array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'startDate' => '-0d',
						'format'=>'yyyy-mm-dd'		
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-3',
				),
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>		
		
		<?php
		echo $form->switchGroup($model, 'isDisabled',
			array(
				'widgetOptions' => array(
						'options' => array(
														'onColor'=> 'warning', // 'primary', 'info', 'success', 'warning', 'danger', 'default'
														'onText'=> 'YES',
														'offText' => 'NO', 
														'offColor'=> 'success',
												),
				)
			)
		); ?>
		
		<?php echo $form->textFieldGroup($model,'latitude',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textFieldGroup($model,'longitude',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textFieldGroup($model,'priority',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
		<?php echo $form->textFieldGroup($model,'position',array('wrapperHtmlOptions' => array('class' => 'col-sm-3',))); ?>
				
				
				
<div class="form-group"><label class="col-sm-3 control-label" for="Ads_longitude"></label><div class ='col-sm-2' >
<?php 
$form->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Preview',
        'context' => 'primary',
        'htmlOptions' => array(
        	'id' => 'preview',	
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
//         		'class' => 'form-control'
        ),
//     	'wrapperHtmlOptions' => array('class' => 'col-sm-3')
	    				
    )
);
?>

</div></div>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'myModal'
    		
)
); ?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Modal header</h4>
    </div>
 
    <div class="modal-body">
<iframe id="previewframe" src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" height="700px" width="560px" scrolling="yes"></iframe>
    </div>
 
    <div class="modal-footer">
        <?php 
        
        $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Save changes',
//                 'url' => '#',
                'htmlOptions' => array('type'=>'Submit', "value"=>"Submit"),
            )
        ); ?>
        
        				<?php // echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>"pull-right")); ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('class'=>'pull-right', 'data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
 
<?php $this->endWidget(); ?>

		<div class="form-group">
		<div class="col-sm-3 col-sm-9">

		</div>
	</div>
</div>

<?php  $this->endWidget(); ?>
</div>
<script type="text/javascript">
$("#preview").click(function(){

	adType = $('#Ads_adType').val()
	
	if(adType==1)
		baseUri = 'iframe/post/'
	else if(adType==2)
		baseUri = 'iframe/storeItem/'
	else if(adType==3)
		baseUri = 'iframe/news/'
					
	entityId = $('#Ads_entityId').val();

	url = 'http://<?php echo isset($_SERVER['FT_DEFAULT'])? $_SERVER['FT_DEFAULT'] : 'foodtalk.in' ?>/'+baseUri+entityId;

	$("#previewframe").attr('src',url); 
});
</script>
