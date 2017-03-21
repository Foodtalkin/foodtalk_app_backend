<?php

// class CityController extends ServiceBaseController
class StoreOfferController extends RestaurantServiceBaseController
{
    public function actionAdd()
    {
        $apiName = 'storeOffer/add';
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

                                	$isNewOffer = true;
                	
                	if(isset($_JSON['id']) && !empty ($_JSON['id'])){
                		$offer = StoreOffer::model()->findByPk($_JSON['id']);
                		if(empty($offer)){
                			throw new Exception(print_r('Invalid offer id', true), WS_ERR_WONG_VALUE);
                		}
                		$isNewOffer = false;
                	}
                	else
	                	$offer = new StoreOffer('create_api');
                	
                	if(isset($_JSON['validTill']) && !empty ($_JSON['validTill']))
                		$offer->validTill = trim(filter_var($_JSON['validTill'], FILTER_SANITIZE_STRING));

                	
                	if($isNewOffer){
                		if(isset($_JSON['totalQuantity'] ) && ! empty ( $_JSON ['totalQuantity'] ))
							$offer->availableQuantity = trim ( filter_var ( $_JSON ['totalQuantity'], FILTER_SANITIZE_NUMBER_INT));
                	}else
                		if(isset($_JSON['totalQuantity']) && !empty ($_JSON['totalQuantity']))
                			$offer->availableQuantity = trim(filter_var($_JSON['totalQuantity'], FILTER_SANITIZE_NUMBER_INT)) - $offer->totalQuantity + $offer->availableQuantity;
                		 
                	
                	if(isset($_JSON['totalQuantity']) && !empty ($_JSON['totalQuantity']))
                		$offer->totalQuantity = trim(filter_var($_JSON['totalQuantity'], FILTER_SANITIZE_NUMBER_INT));
                	 
                	if(isset($_JSON['limitPerUser']) && !empty ($_JSON['limitPerUser']))
                		$offer->limitPerUser = trim(filter_var($_JSON['limitPerUser'], FILTER_SANITIZE_NUMBER_INT));
                	 
                	if(isset($_JSON['couponCode']) && !empty ($_JSON['couponCode']))
                		$offer->couponCode = filter_var($_JSON['couponCode'], FILTER_SANITIZE_STRING);

                	if(isset($_JSON['subType']) && !empty ($_JSON['subType']))
                		$offer->subType = trim(filter_var($_JSON['subType'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['autoGenerateCode']))
                		$offer->autoGenerateCode = trim(filter_var($_JSON['autoGenerateCode'], FILTER_SANITIZE_NUMBER_INT));
                	
                	if(isset($_JSON['redemptionUrl'])){
	                	if(empty (trim($_JSON['redemptionUrl'])))
	                		$offer->redemptionUrl = null;
	                	else 	
	                		$offer->redemptionUrl = trim(filter_var($_JSON['redemptionUrl'], FILTER_SANITIZE_STRING));
                	}
                	
                	if(isset($_JSON['redemptionPhone']) && !empty ($_JSON['redemptionPhone'])){
	                	if(empty (trim($_JSON['redemptionPhone'])))
                			$offer->redemptionPhone = null;
                		else
                			$offer->redemptionPhone = trim(filter_var($_JSON['redemptionPhone'], FILTER_SANITIZE_STRING));
                	}
                	 
                	
                	if ($isNewOffer)
	                	$StoreItem = new StoreItem('create_api');
                	else 
                		$StoreItem = $offer->storeItem;
                		
                	if(isset($_JSON['title']) && !empty ($_JSON['title']))
						$StoreItem->title = trim($_JSON['title']);
                	
                	if(isset($_JSON['coverImage']) && !empty ($_JSON['coverImage']))
	                	$StoreItem->coverImage = filter_var($_JSON['coverImage'], FILTER_SANITIZE_STRING);
                	
                	if(isset($_JSON['cardImage']) && !empty ($_JSON['cardImage']))
	                	$StoreItem->cardImage = filter_var($_JSON['cardImage'], FILTER_SANITIZE_STRING);
                	
                	if(isset($_JSON['actionButtonText']) && !empty ($_JSON['actionButtonText']))
	                	$StoreItem->actionButtonText = trim($_JSON['actionButtonText']);
                	
                	if(isset($_JSON['cardActionButtonText']) && !empty ($_JSON['cardActionButtonText']))
                		$StoreItem->cardActionButtonText = trim($_JSON['cardActionButtonText']);
                	
                	
                	if(isset($_JSON['description']) && !empty ($_JSON['description']))
                		$StoreItem->description = trim($_JSON['description']);
                	
                	if(isset($_JSON['shortDescription']) && !empty ($_JSON['shortDescription']))
                		$StoreItem->shortDescription = trim($_JSON['shortDescription']);
                	
                	if(isset($_JSON['cityText']) && !empty ($_JSON['cityText']))
                		$StoreItem->cityText = trim(filter_var($_JSON['cityText'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['costType']) && !empty ($_JSON['costType']))
                		$StoreItem->costType = trim(filter_var($_JSON['costType'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['costOnline']) && !empty ($_JSON['costOnline']))
                		$StoreItem->costOnline = trim(filter_var($_JSON['costOnline'], FILTER_SANITIZE_NUMBER_INT));
                	
                	if(isset($_JSON['costPoints']) && !empty ($_JSON['costPoints']))
                		$StoreItem->costPoints = trim(filter_var($_JSON['costPoints'], FILTER_SANITIZE_NUMBER_INT));
                	
                	if(isset($_JSON['termConditionsLink']) && !empty ($_JSON['termConditionsLink']))
                		$StoreItem->termConditionsLink = filter_var($_JSON['termConditionsLink'], FILTER_SANITIZE_STRING);
                	
                	if(isset($_JSON['thankYouText']) && !empty ($_JSON['thankYouText']))
                		$StoreItem->thankYouText = trim(filter_var($_JSON['thankYouText'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['postPurchaseInstructions']) && !empty ($_JSON['postPurchaseInstructions']))
                		$StoreItem->postPurchaseInstructions = trim(filter_var($_JSON['postPurchaseInstructions'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['startDate']) && !empty ($_JSON['startDate']))
                		$StoreItem->startDate = trim(filter_var($_JSON['startDate'], FILTER_SANITIZE_STRING));
                	
                	if(isset($_JSON['endDate']) && !empty ($_JSON['endDate']))
                		$StoreItem->endDate = trim(filter_var($_JSON['endDate'], FILTER_SANITIZE_STRING));
	                
                	if(isset($_JSON['isDisabled'])){
                		$StoreItem->isDisabled = trim(filter_var($_JSON['isDisabled'], FILTER_SANITIZE_NUMBER_INT));
                		$offer->isDisabled = trim(filter_var($_JSON['isDisabled'], FILTER_SANITIZE_NUMBER_INT));
                	}

                	
                	
                	if($isNewOffer){
	                	if(isset($_JSON['type'])  && strtoupper(trim($_JSON['type'])) =='DINE-IN' ){
	                		$StoreItem->type = 'DINE-IN';
// 	                		$offer->subType = 'UNIQUE_CODE';
	                	}else
		                	$StoreItem->type = 'OFFER'; 
                	}
                	$StoreItem->save();
                	 
                	if ($StoreItem->hasErrors()){
                		throw new Exception(print_r($StoreItem->getErrors(), true), WS_ERR_UNKNOWN);
                	}
                	if ($isNewOffer)
	                	$offer->storeItemId = $StoreItem->id;
                	
					$offer->save();
                	
					if ($offer->hasErrors()){
						throw new Exception(print_r($offer->getErrors(), true), WS_ERR_UNKNOWN);
					}
                	
					$result = array(
							'api' => $apiName,
							'apiMessage' => 'Event saved successfully.',
							'status' => 'OK',
							'storeItemId' => $StoreItem->id,
							'storeOfferId' => $offer->id,
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
		$_JSON = $this->getJsonInput ();
		$page = 1;
		$status = 'active';
		$message = null;		
		
		if (isset ( $_JSON ['page'] ) && $_JSON ['page'])
			$page = filter_var ( $_JSON ['page'], FILTER_SANITIZE_NUMBER_INT );
		
		if (isset ( $_JSON ['searchText'] ) && $_JSON ['searchText'])
			$this->options ['searchText'] = filter_var ( $_JSON ['searchText'], FILTER_SANITIZE_STRING );
		
		if (isset ( $_JSON ['status'] ) && $_JSON ['status'] && self::isManager ())
			$status = filter_var ( $_JSON ['status'], FILTER_SANITIZE_STRING );
		
		if (isset ( $_JSON ['type'] ) && $_JSON ['type'])
			$this->options ['type'] = filter_var ( $_JSON ['type'], FILTER_SANITIZE_STRING );
			
		$recordCount = 9;
		if (isset ( $_JSON ['recordCount'] ) && $_JSON ['recordCount'])
			$recordCount = filter_var ( $_JSON ['recordCount'], FILTER_SANITIZE_NUMBER_INT );
		
		$response = StoreOffer::getOffers( $page, $status, $this->options, $recordCount );
		
		if(empty($response))
			$message = 'no such offers';
			
		return	$this->sendResponse($response,self::SUCCESS_OK, $message);
    
    }
    
	public function actionGet() {
		
		$_JSON = $this->getJsonInput ();
		$status = 'active';
		$id = 0;
		$storeItemId = 0;
		$message = null;
		
		if (isset ( $_JSON ['status'] ) && $_JSON ['status'] && self::isManager ( $user ))
			$status = filter_var ( $_JSON ['status'], FILTER_SANITIZE_STRING );
		
		if (isset ( $_JSON ['id'] ) && ! empty ( $_JSON ['id'] ))
			$id = trim ( filter_var ( $_JSON ['id'], FILTER_SANITIZE_NUMBER_INT ) );
		
		if (isset ( $_JSON ['storeItemId'] ) && ! empty ( $_JSON ['storeItemId'] ))
			$storeItemId = trim ( filter_var ( $_JSON ['storeItemId'], FILTER_SANITIZE_NUMBER_INT ) );
		
		$storeOffer = StoreOffer::getThisOffer ( $id, $storeItemId, $status, $this->options );
		
		$clamedUsers = [];
		if(!empty($storeOffer))
			$clamedUsers = StorePurchase::getClamedUsers($storeOffer['storeItemId']);
		else 
			$message = 'no such offers';
		
		$response = array (
				'storeOffer' => $storeOffer,
				'clamedUsers'=>$clamedUsers
		);
		return	$this->sendResponse($response,self::SUCCESS_OK, $message);
		
	}

    public function actionAddCoupon()
    {
    	$apiName = 'storeOffer/addCoupon';
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
    
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				if(isset($_JSON['storeOfferId']) && !empty ($_JSON['storeOfferId']))
    					$offer = StoreOffer::model()->findByPk($_JSON['storeOfferId']);
    
    				if(empty($offer)){
    					throw new Exception(print_r('Invalid offer id', true), WS_ERR_WONG_VALUE);
    				}
    
    				if(isset($_JSON['coupons']) && !empty ($_JSON['coupons']))
    					$coupons = $_JSON['coupons'];
    				else
    					throw new Exception(print_r('no coupon code provided', true), WS_ERR_POST_PARAM_MISSED);
    
    				foreach ($coupons as $code){
    					$coupon = new StoreCoupon('create_api');
    					$coupon->storeOfferId = $offer->id;
    					$coupon->code = $code;
    					$coupon->save();
    						
    					if ($coupon->hasErrors()){
    						throw new Exception(print_r($coupon->getErrors(), true), WS_ERR_UNKNOWN);
    					}
    				}
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Coupons added successfully',
    						'status' => 'OK'
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
    
 
    
    public function actionAddRestaurant()
    {
    	$apiName = 'storeOffer/addRestaurant';
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
    
    			if (is_null($user) and $user->id  > 0)
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				if(isset($_JSON['storeOfferId']) && !empty ($_JSON['storeOfferId']))
    					$offer = StoreOffer::model()->findByPk($_JSON['storeOfferId']);
    
    				if(empty($offer)){
    					throw new Exception(print_r('Invalid offer id', true), WS_ERR_WONG_VALUE);
    				}
    
    				if(isset($_JSON['restaurant']) && !empty ($_JSON['restaurant']))
    					$coupons = $_JSON['restaurant'];
    				else
    					throw new Exception(print_r('no restaurants provided', true), WS_ERR_POST_PARAM_MISSED);
    
    				foreach ($coupons as $code){
    					$coupon = new StoreCoupon('create_api');
    					$coupon->storeOfferId = $offer->storeItemId;
    					$coupon->code = $code;
    					$coupon->save();
    
    					if ($coupon->hasErrors()){
    						throw new Exception(print_r($coupon->getErrors(), true), WS_ERR_UNKNOWN);
    					}
    				}
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Coupons added successfully',
    						'status' => 'OK'
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
    
    
    
	public function actionGetCoupon()
	{
		$_JSON = $this->getJsonInput ();
		$message = null;
		
		if(isset($_JSON['code']) && !empty ($_JSON['code']))
			$response = StoreCoupon::getThisCoupon(trim(filter_var($_JSON['code'], FILTER_SANITIZE_STRING)), $this->options);
		else
			throw new Exception(print_r('Error : No Coupon code provided!', true), self::NOT_ACCEPTABLE);
		
		if(empty($response))
			throw new Exception(print_r('Error : Invalid Coupon code!', true), self::NOT_ACCEPTABLE);

		
		$purchase = StorePurchase::model()->findByAttributes(array('storeItemId'=>$response['storeItemId'], 'userId'=>$response['userId']));
		$metadata = json_decode($purchase->metaData, true);
			
		if(time() > strtotime($metadata['validTill']))
			throw new Exception(print_r('Error : Coupon code Expired!', true), self::NOT_ACCEPTABLE);
		
		$response['validTill'] = $metadata['validTill'];
		
		if(empty($response))
			$message = 'Error : Invalid Coupon code!';
			
		return	$this->sendResponse($response,self::SUCCESS_OK, $message);
	}
    
 
	public function actionRedeemHistory()
	{
		$_JSON = $this->getJsonInput ();
		$status = 'active';
		$id = 0;
		$storeItemId = 0;
		$message = null;
		
		if (isset ( $_JSON ['status'] ) && $_JSON ['status'] && self::isManager ( $user ))
			$status = filter_var ( $_JSON ['status'], FILTER_SANITIZE_STRING );
		
		if (isset ( $_JSON ['id'] ) && ! empty ( $_JSON ['id'] ))
			$id = trim ( filter_var ( $_JSON ['id'], FILTER_SANITIZE_NUMBER_INT ) );
		
		if (isset ( $_JSON ['storeItemId'] ) && ! empty ( $_JSON ['storeItemId'] ))
			$storeItemId = trim ( filter_var ( $_JSON ['storeItemId'], FILTER_SANITIZE_NUMBER_INT ) );
		
		$storeOffer = StoreOffer::getThisOffer ( $id, $storeItemId, $status, $this->options );
		
		$purchaseOptions = array('isUsed'=>1);
		
		$redeemUsers = [];
		if(!empty($storeOffer))
			$redeemUsers = StorePurchase::getClamedUsers($storeOffer['storeItemId'], $purchaseOptions );
		else 
			$message = 'no such offers';
		
		$response = array (
				'storeOffer' => $storeOffer,
				'redeemUsers'=>$redeemUsers
		);
		return	$this->sendResponse($response,self::SUCCESS_OK, $message);
		
	}
	
	
	
	public function actionRedeemCoupon(){
		
		$_JSON = $this->getJsonInput();
		
		if(isset($_JSON['code']) && !empty ($_JSON['code']))
			$coupon = StoreCoupon::getThisCoupon(trim(filter_var($_JSON['code'], FILTER_SANITIZE_STRING)), $this->options);
		else
			throw new Exception(print_r('Error : No Coupon code provided!', true), self::NOT_ACCEPTABLE);
						
		if(empty($coupon)){
			throw new Exception(print_r('Error : Invalid Coupon code!', true), self::NOT_ACCEPTABLE);
		}
		
		if(boolval($coupon['isUsed'])){
			throw new Exception(print_r('Error : Coupon code Already Used!', true), self::NOT_ACCEPTABLE);
		}
		
		if($coupon['userId'] > 0){
						
			
			$purchase = StorePurchase::model()->findByAttributes(array('storeItemId'=>$coupon['storeItemId'], 'userId'=>$coupon['userId']));
			$metadata = json_decode($purchase->metaData, true);
			
			if(time() > strtotime($metadata['validTill']))
				throw new Exception(print_r('Error : Coupon code Expired!', true), self::NOT_ACCEPTABLE);
			
			$purchase->isUsed = 1;
			$purchase->save();
			
			$res = StoreCoupon::model()->updateByPk( $coupon['id'], array('isUsed'=>1) );
		
		}else{
			throw new Exception(print_r('Error : Invalid Coupon code!', true), self::NOT_ACCEPTABLE);
		}
		return	$this->sendResponse([],self::SUCCESS_OK);
	}

}