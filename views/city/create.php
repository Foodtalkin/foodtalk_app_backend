<script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/abound/js/selectize.js"></script>
<?php
/* @var $this CityController */
/* @var $model City */

$this->breadcrumbs=array(
	'Cities'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Dashboard', 'url'=>array('site/dashboard')),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

<h1>Create City</h1>

<script type="text/javascript">
$( document ).ready(function() {
	$('#duplicateId').selectize({
	    valueField: 'place_id',
	    labelField: 'description',
	    searchField: 'description',
	    create: false,
	    render: {
	        option: function(item, escape) {

	            return '<div>' +
	                '<span class="title">' +
	                    '<span class="name">' + escape(item.description) + '</span>' +
	                '</span>' +
// 	                    '<br><span class="by">' + escape(item.address) +', ('+ escape(item.region) +')</span>' +
	            '</div>';
	        }
	    },
	    load: function(query, callback) {
	        if (!query.length) return callback();
	        $.ajax({
	        	 accepts: {
	        		    mycustomtype: 'text/plain'
	        		  },
		        url: 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='+encodeURIComponent(query)+'&types=(cities)&key=AIzaSyCkhfzw_JLdFtJkwkHEUNBtsHm_GRNF59Y',
//	        	url: '<?php echo Yii::app()->request->baseUrl; ?>/index.php/service/restaurant/list?sessionId=GUEST&searchText=' + encodeURIComponent(query),
	            type: 'GET',
	            error: function() {
	                callback();
	            },
	            success: function(res) {
	                callback(res.restaurants);
	            }
	        });
	    }
	});
	});
</script>

<select name="duplicateId" id="duplicateId">
</select>

<?php // $this->renderPartial('_form', array('model'=>$model)); 