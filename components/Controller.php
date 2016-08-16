<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    
    
    public static final  function requestBatcave($api , array $postData, $method = 'POST', $authRequired = false) {
    
    	if($authRequired)
    	{
//     		autharization code if required
    	}
    
    	$apiHost = 'http://api.foodtalk.in/';
//     	$apiHost = 'http://local-api.foodtalkindia.com/';
    	
    	
    		$data_string = json_encode($postData, true);
    		$url = $apiHost.$api;
    
    		$ch = curl_init($url);
    		
    		if($method=='post'){    		
	    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	    		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    		curl_setopt($ch, CURLOPT_USERAGENT, 'FoodTalk 52app 1.0');
	    		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    				'Content-Type: application/json',
	    				'Content-Length: ' . strlen($data_string))
	    		);
    		}
    		
    		$response = curl_exec($ch);
    		$err = curl_error($ch);
    		curl_close($ch);
    
    		if ($err) {
    			error_log("cURL Error # : " . $err);
    			return array();
    		} else {
    			return  json_decode($response, true);
    		}
    
    }
    
}