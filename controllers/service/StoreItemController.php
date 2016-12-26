<?php

// class CityController extends ServiceBaseController
class StoreItemController extends ServiceBaseController
{
	
    public function actionListPurchase()
    {
    	$apiName = 'storeItem/listPurchase';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    	
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    	
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				
    				$page = 1;
    				$type = false;
    				$storePurchase = array();
    				
    				if(isset($_JSON['page']) && $_JSON['page'])
    					$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    				
    				if(isset($_JSON['type']) && $_JSON['type'])
    					$type = filter_var($_JSON['type'], FILTER_SANITIZE_STRING);
    				
    				
//     				if(isset($_JSON['status']) && $_JSON['status'] && $user->role == 'manager')
//     					$status = filter_var($_JSON['status'], FILTER_SANITIZE_STRING);
    				
    				$storePurchase = StoreItem::getStorePurchase($page, $type);
    				
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Records fetched successfully',
    						'status' => 'OK',
    						'storePurchase' => $storePurchase
    				);
    				
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    public function actionListItems()
    {
    	$apiName = 'storeItem/listItems';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    	 
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			 
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    
    				$page = 1;
    				$type = false;
    				$storePurchase = array();
    
    				if(isset($_JSON['page']) && $_JSON['page'])
    					$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    
    				if(isset($_JSON['type']) && $_JSON['type'])
    					$type = filter_var($_JSON['type'], FILTER_SANITIZE_STRING);
    
//     				if(isset($_JSON['status']) && $_JSON['status'] && $user->role == 'manager')
//     					$status = filter_var($_JSON['status'], FILTER_SANITIZE_STRING);
    
					$storeItems = StoreItem::getStoreItems($page, $userId, $type);
					$profile = User::getProfileById($userId, $userId);
					
					$result = array(
							'api' => $apiName,
    						'apiMessage' => 'Records fetched successfully',
    						'status' => 'OK',
							'profile'=>$profile,
    						'storeItems' => $storeItems
    				);
    
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    
    public function actionPurchase()
	{
    	$apiName = 'storeItem/purchase';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    	
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    	
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				
    				if(isset($_JSON['storeItemId']) && !empty ($_JSON['storeItemId']))
//     					$item = StoreItem::model()->findByPk($_JSON['storeItemId']);
    					$item = StoreItem::getThisItem($_JSON['storeItemId'], $userId);
    				

    				if(empty($item)){
    					throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
    				}
    				
    				if($item['iPurchasedIt'] > 0 && $item['type'] == 'OFFER'){
    					throw new Exception(print_r('You already have this offer', true), WS_ERR_REQUEST_NOT_ACCEPTED);
    				}
    				
    				$profile = User::getProfileById($userId, $userId);
    				
    				if(isset($_JSON['costPoints']) && !empty ($_JSON['costPoints']))
    					$costPoints = trim(filter_var($_JSON['costPoints'], FILTER_SANITIZE_NUMBER_INT));
    				
    				$quantity = 1;
    				if(isset($_JSON['quantity']) && !empty ($_JSON['quantity']))
    					$quantity = trim(filter_var($_JSON['quantity'], FILTER_SANITIZE_NUMBER_INT));
    				
					if($costPoints*$quantity > $profile['avilablePoints'])    				
						throw new Exception(print_r('cannnot process request insufficient points', true), WS_ERR_REQUEST_NOT_ACCEPTED);
					
					
					
//     				die('dead');
    					
    				$purchase = new StorePurchase('create_api');	
    				
    				$purchase->storeItemId = $item['storeItemId'];
    				$purchase->userId = $user->id;
    				$purchase->costType = $item['costType'];
    				$purchase->facebookId = $user->facebookId;
    				
    				
    				
    				if(isset($_JSON['costOnline']) && !empty ($_JSON['costOnline']))
    					$purchase->costOnline = trim(filter_var($_JSON['costOnline'], FILTER_SANITIZE_NUMBER_INT));
    				

    				if(isset($_JSON['costPoints']) && !empty ($_JSON['costPoints']))
    					$purchase->costPoints = trim(filter_var($_JSON['costPoints'], FILTER_SANITIZE_NUMBER_INT));
    				
    				if(isset($_JSON['quantity']) && !empty ($_JSON['quantity']))
	    				$purchase->quantity = trim(filter_var($_JSON['quantity'], FILTER_SANITIZE_NUMBER_INT));
    				
    				
    				if(isset($_JSON['metaData']) && !empty ($_JSON['metaData']))
    					$purchase->metaData = json_encode($_JSON['metaData']); 
    				
    				$purchase->save();
    				if ($purchase->hasErrors()){
    					throw new Exception(print_r($purchase->getErrors(), true), WS_ERR_UNKNOWN);
    				}
    				
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'purchase successfully',
    						'status' => 'OK',
    						'storePurchaseId' => $purchase->id
    				);
    				
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
 
    
    public function actionAddCity()
    {
    	$apiName = 'storeItem/addCity';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    	 
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			 
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    
    				if(isset($_JSON['storeItemId']) && !empty ($_JSON['storeItemId']))
    					$item = StoreItem::model()->findByPk($_JSON['storeItemId']);
    
    				if(empty($item)){
    					throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
    				}
    				
    				if(isset($_JSON['cities']) && !empty ($_JSON['cities']))
	    				$cities = $_JSON['cities'];
    				else 
    					throw new Exception(print_r('no city provided', true), WS_ERR_POST_PARAM_MISSED);
    				
    				StoreItemCity::model()->deleteAllByAttributes(array('storeItemId'=>$item->id));
    				
    				foreach ($cities as $cityId){
    					$city = new StoreItemCity('create_api');
    					$city->storeItemId = $item->id;
    					$city->cityId = $cityId;
    					$city->save();
    					
    					if ($city->hasErrors()){
    						throw new Exception(print_r($city->getErrors(), true), WS_ERR_UNKNOWN);
    					}
    					
    				}
    				
//     				$purchase->save();
//     				if ($purchase->hasErrors()){
//     					throw new Exception(print_r($purchase->getErrors(), true), WS_ERR_UNKNOWN);
//     				}
    
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'city added successfully',
    						'status' => 'OK'
//     						'storePurchaseId' => $purchase->id
    				);
    
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    public function actionAddUserFields()
    {
    	$apiName = 'storeItem/addUserFields';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    
    				if(isset($_JSON['storeItemId']) && !empty ($_JSON['storeItemId']))
    					$item = StoreItem::model()->findByPk($_JSON['storeItemId']);
    
    				if(empty($item)){
    					throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
    				}
    
    				if(isset($_JSON['userFields']) && !empty ($_JSON['userFields']))
    					$userFields = $_JSON['userFields'];
    				else
    					throw new Exception(print_r('no user fields provided', true), WS_ERR_POST_PARAM_MISSED);
    
    				StoreItemUserInfo::model()->deleteAllByAttributes(array('storeItemId'=>$item->id));
    
    				foreach ($userFields as $FieldId){
    					$userField = new StoreItemUserInfo('create_api');
    					$userField->storeItemId = $item->id;
    					$userField->storeUserFieldsId = $cityId;
    					$userField->save();
    					
    					if ($userField->hasErrors()){
    						throw new Exception(print_r($userField->getErrors(), true), WS_ERR_UNKNOWN);
    					}
    				}
    
    
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'User fields added successfully',
    						'status' => 'OK'
    						//     						'storePurchaseId' => $purchase->id
    				);
    
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    public function actionGetCities()
    {
    	$apiName = 'storeItem/getCities';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    
    				if(isset($_JSON['storeItemId']) && !empty ($_JSON['storeItemId']))
    					$item = StoreItem::model()->findByPk($_JSON['storeItemId']);
    
    				if(empty($item)){
    					throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
    				}
        
    				$cities = StoreItemCity::getCities($item->id);
    
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'city added successfully',
    						'status' => 'OK',
    						'cities' => $cities
    				);
    
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    public function actionGetUserFields()
    {
    	$apiName = 'storeItem/getUserFields';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    
    				if(isset($_JSON['storeItemId']) && !empty ($_JSON['storeItemId']))
    					$item = StoreItem::model()->findByPk($_JSON['storeItemId']);
    
    				if(empty($item)){
    					throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
    				}
    
    				$UserFields = StoreItemUserInfo::getFields($item->id);
    
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'city added successfully',
    						'status' => 'OK',
    						'userFields' => $UserFields
    				);
    
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    public function actionListUserFields(){
    
    	$apiName = 'storeItem/listUserFields';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    	 
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			 
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    
    				$userFields = StoreUserFields::getFields();
    
    				$result = array(
    					'api' => $apiName,
    					'apiMessage' => 'Records fetched successfully',
    					'status' => 'OK',
    					'userFields' => $userFields
    				);
    
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
    }
    
    public function actionGetPurchaseDetailes(){
    
    	$apiName = 'storeItem/GetPurchaseDetailes';
    	$sessionId = null;
    	
    	$_JSON = $this->getJsonInput();
    	
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else
    		{
    			//             	$user = true;
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    	
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    	
    				if(isset($_JSON['storeItemId']) && !empty ($_JSON['storeItemId']))
    					$item = StoreItem::model()->findByPk($_JSON['storeItemId']);
    	
    				if(empty($item)){
    					throw new Exception(print_r('Invalid Item id', true), WS_ERR_WONG_VALUE);
    				}
    	
    				$UserFields = StorePurchase::getPurchaseDetailes($item->id);
    	
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'city added successfully',
    						'status' => 'OK',
    						'userFields' => $UserFields
    				);
    	
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    	
    }
    
    
    
}