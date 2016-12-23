<?php

// class CityController extends ServiceBaseController
class StoreEventController extends ServiceBaseController
{
    public function actionAdd()
    {
        $apiName = 'storeEvent/add';
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
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'unautharized access');
                else{

                	$isNewEvent = true;
                	
                	if(isset($_JSON['id']) && !empty ($_JSON['id'])){
                		$event = StoreEvent::model()->findByPk($_JSON['id']);
                		if(empty($event)){
                			throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
                		}
                		$isNewEvent = false;
                	}
                	else
	                	$event = new StoreEvent('create_api');
                	
                	if(isset($_JSON['dateTime']) && !empty ($_JSON['dateTime']))
                		$event->dateTime = trim(filter_var($_JSON['dateTime'], FILTER_SANITIZE_STRING));
                	 
                	if(isset($_JSON['venue']) && !empty ($_JSON['venue']))
                		$event->venue = trim(filter_var($_JSON['venue'], FILTER_SANITIZE_STRING));
                	 
                	if(isset($_JSON['TotalSeats']) && !empty ($_JSON['TotalSeats']))
                		$event->TotalSeats = trim(filter_var($_JSON['TotalSeats'], FILTER_SANITIZE_NUMBER_INT));
                	 
                	if(isset($_JSON['TotalSeats']) && !empty ($_JSON['TotalSeats']))
                		$event->avilableSeat = trim(filter_var($_JSON['TotalSeats'], FILTER_SANITIZE_NUMBER_INT));
                	 
                	if(isset($_JSON['earlyBirdCost']) && !empty ($_JSON['earlyBirdCost']))
                		$event->earlyBirdCost = trim(filter_var($_JSON['earlyBirdCost'], FILTER_SANITIZE_NUMBER_INT));
                	 
                	if(isset($_JSON['earlyBirdPaymentLink']) && !empty ($_JSON['earlyBirdPaymentLink']))
                		$event->earlyBirdPaymentLink = filter_var($_JSON['earlyBirdPaymentLink'], FILTER_SANITIZE_STRING);
                	 
                	if(isset($_JSON['subType']) && !empty ($_JSON['subType']))
                		$event->subType = trim(filter_var($_JSON['subType'], FILTER_SANITIZE_STRING));
                	 
                	if(isset($_JSON['paymentLink']) && !empty ($_JSON['paymentLink']))
                		$event->paymentLink = filter_var($_JSON['paymentLink'], FILTER_SANITIZE_STRING);
                	
                	if ($isNewEvent)
	                	$StoreItem = new StoreItem('create_api');
                	else 
                		$StoreItem = $event->storeItem;
                		
                	if(isset($_JSON['title']) && !empty ($_JSON['title']))
						$StoreItem->title = trim(filter_var($_JSON['title'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['coverImage']) && !empty ($_JSON['coverImage']))
	                	$StoreItem->coverImage = filter_var($_JSON['coverImage'], FILTER_SANITIZE_STRING);
                	
                	if(isset($_JSON['cardImage']) && !empty ($_JSON['cardImage']))
	                	$StoreItem->cardImage = filter_var($_JSON['cardImage'], FILTER_SANITIZE_STRING);
                	
                	if(isset($_JSON['actionButtonText']) && !empty ($_JSON['actionButtonText']))
	                	$StoreItem->actionButtonText = trim(filter_var($_JSON['actionButtonText'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['description']) && !empty ($_JSON['description']))
                		$StoreItem->description = trim(filter_var($_JSON['description'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['costType']) && !empty ($_JSON['costType']))
                		$StoreItem->costType = trim(filter_var($_JSON['costType'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['costOnline']) && !empty ($_JSON['costOnline']))
                		$StoreItem->costOnline = trim(filter_var($_JSON['costOnline'], FILTER_SANITIZE_NUMBER_INT));
                	
                	if(isset($_JSON['costPoints']) && !empty ($_JSON['costPoints']))
                		$StoreItem->costPoints = trim(filter_var($_JSON['costPoints'], FILTER_SANITIZE_NUMBER_INT));
                	
                	if(isset($_JSON['termConditionsLink']) && !empty ($_JSON['termConditionsLink']))
                		$StoreItem->cardImage = filter_var($_JSON['termConditionsLink'], FILTER_SANITIZE_STRING);
                	
                	if(isset($_JSON['thankYouText']) && !empty ($_JSON['thankYouText']))
                		$StoreItem->thankYouText = trim(filter_var($_JSON['thankYouText'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['postPurchaseInstructions']) && !empty ($_JSON['postPurchaseInstructions']))
                		$StoreItem->postPurchaseInstructions = trim(filter_var($_JSON['postPurchaseInstructions'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['startDate']) && !empty ($_JSON['startDate']))
                		$StoreItem->startDate = trim(filter_var($_JSON['startDate'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['endDate']) && !empty ($_JSON['endDate']))
                		$StoreItem->endDate = trim(filter_var($_JSON['endDate'], FILTER_SANITIZE_STRING));
	                
                	if(isset($_JSON['isDisabled']) && !empty ($_JSON['isDisabled']))
                		$StoreItem->isDisabled = trim(filter_var($_JSON['isDisabled'], FILTER_SANITIZE_NUMBER_INT));

                	
                	$StoreItem->type = 'EVENT'; 

                	$StoreItem->save();
                	 
                	if ($StoreItem->hasErrors()){
                		throw new Exception(print_r($StoreItem->getErrors(), true), WS_ERR_UNKNOWN);
                	}
                	if ($isNewEvent)
	                	$event->storeItemId = $StoreItem->id;
                	
					$event->save();
                	
					if ($event->hasErrors()){
						throw new Exception(print_r($event->getErrors(), true), WS_ERR_UNKNOWN);
					}
                
					$result = array(
							'api' => $apiName,
							'apiMessage' => 'Event saved successfully.',
							'status' => 'OK',
							'storeItemId' => $StoreItem->id,
							'storeEventId' => $event->id,
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
    
    
    
    public function actionList()
    {
    	$apiName = 'storeEvent/list';
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
    				$storeEvents = array();
    				$options = array();
    				
    				if(isset($_JSON['page']) && $_JSON['page'])
    					$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    				
    				$status = 'upcomming';
    				
    				if(isset($_JSON['status']) && $_JSON['status'] && $user->role == 'manager')
    					$status = filter_var($_JSON['status'], FILTER_SANITIZE_STRING);
    				
    				if(isset($_JSON['city']) && $_JSON['city'])
    					$options['city'] = filter_var($_JSON['city'], FILTER_SANITIZE_STRING);
    				
    				$storeEvents = StoreEvent::getStoreEvents($page, $status, $options);
    				
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Records fetched successfully',
    						'status' => 'OK',
    						'storeEvents' => $storeEvents
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
    
//     public function actionPurchase()
// 	{
//     	$apiName = 'storeEvent/purchase';
//     	$sessionId = null;
    
//     	$_JSON = $this->getJsonInput();
    	
//     	try
//     	{
//     		if(!isset($_JSON) || empty($_JSON))
//     			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
//     		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
//     			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
//     		else
//     		{
//     			//             	$user = true;
//     			$userId = $this->isAuthentic($_JSON['sessionId']);
//     			$user = User::model()->findByPk($userId);
    	
//     			if (is_null($user) and $user->id  > 0)
//     				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
//     			else
//     			{
    				
//     				if(isset($_JSON['id']) && !empty ($_JSON['id']))
//     					$event = StoreEvent::model()->findByPk($_JSON['id']);

//     					if(empty($event)){
//     						throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
//     					}
    					
//     				$purchase = new StorePurchase('create_api');	
    				
//     				$purchase->storeItemId = $event->storeItemId;
//     				$purchase->entityId = $event->id;
//     				$purchase->userId = $user->id;
//     				$purchase->costType = $event->storeItem->costType;
    				
    				
//     				if(isset($_JSON['costOnline']) && !empty ($_JSON['costOnline']))
//     					$purchase->costOnline = trim(filter_var($_JSON['costOnline'], FILTER_SANITIZE_NUMBER_INT));
    				

//     				if(isset($_JSON['costPoints']) && !empty ($_JSON['costPoints']))
//     					$purchase->costPoints = trim(filter_var($_JSON['costPoints'], FILTER_SANITIZE_NUMBER_INT));
    				
//     				if(isset($_JSON['quantity']) && !empty ($_JSON['quantity']))
// 	    				$purchase->quantity = trim(filter_var($_JSON['costPoints'], FILTER_SANITIZE_NUMBER_INT));
    				
    				
//     				if(isset($_JSON['metaData']) && !empty ($_JSON['metaData']))
//     					$purchase->metaData = json_encode($_JSON['metaData']); 
    				
//     				$purchase->save();
//     				if ($purchase->hasErrors()){
//     					throw new Exception(print_r($purchase->getErrors(), true), WS_ERR_UNKNOWN);
//     				}
    				
//     				$result = array(
//     						'api' => $apiName,
//     						'apiMessage' => 'purchase added successfully',
//     						'status' => 'OK',
//     						'storePurchaseId' => $purchase->id
//     				);
    				
//     			}
//     		}
//     	}
//     	catch (Exception $e)
//     	{
//     		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
//     	}
//     	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    
//     }
    
}