<?php
class AdwordsController extends ServiceBaseController
{
    
    /**
     * List adds
     */
    public function actionList()
    {
        $apiName = 'adwords/list';
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
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                	$option = [];
                	
                	if(isset($_JSON['type']) && strlen(trim($_JSON['type']))>1)
                		$option['type'] = filter_var($_JSON['type'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	
                	if(isset($_JSON['latitude']) && $_JSON['latitude'])
                		$option['latitude'] = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                	
                	if(isset($_JSON['longitude']) && $_JSON['longitude'])
                		$option['longitude'] = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                	
                    $addList = Ads::getAds($userId,$option);
                    
                    foreach ($addList as $key => $ad){
                    	
                    	switch ($ad['type']){
                    		case 'post':
                    			$res = self::post(self::POST_GET, array('postId'=>$ad['entityId']));
                    			
								if(isset($res['post']))
	                    			$addList[$key]['content'] = $res['post']; 
								else
									$addList[$key]['content'] = 'no such Post';
                    		break;
                    		case 'storeItem':
                    			$res = self::post(self::STOREITEM_GET, array('storeItemId'=>$ad['entityId']));
                    			 
                    			if(isset($res['storeItem']))
                    				$addList[$key]['content'] = $res['storeItem'];
                    			else
                    				$addList[$key]['content'] = 'no such storeItem';
                    		break;
                    		case 'news':
                    			$res = self::post(self::NEWS_GET, array('id'=>$ad['entityId']));
                    			 
                    			if(isset($res['news']))
                    				$addList[$key]['content'] = $res['news'];
                    			else
                    				$addList[$key]['content'] = 'no such news';
                    		break;
                    	}
                    }
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'adwords fetched successfully.',
                        'status' => 'OK',
                    	'position'=> '9',
                        'result' => $addList 
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
    
    
    
    public function actionRedeedmed()
    {
    	$apiName = 'adwords/redeedmed';
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
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$redeemdList = Adwords::getRedeemdAddsByUser($userId);
    				
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Redeedmed adds fetched successfully.',
    						'status' => 'OK',
    						'result' => $redeemdList
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
    
    
    public function actionBookSlot()
    {
    	$apiName = 'adwords/bookslot';
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
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$quantity = 1;
    				
    				if(isset($_JSON['adId']) && is_numeric($_JSON['adId']) )
						$adId = filter_var($_JSON['adId'], FILTER_SANITIZE_NUMBER_INT);
    				else 
    					$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No adId recived.');
    				
    				if(isset($_JSON['quantity']) && is_numeric($_JSON['quantity']) )
    					$quantity = filter_var($_JSON['quantity'], FILTER_SANITIZE_NUMBER_INT);

    				$avil = Adwords::getAvailability($adId, $userId, $quantity);
    				
    				$add = Adwords::model()->findByPk($adId);
    				
    				
    				if($avil['avilableSlots'] >= $quantity && $avil['avilablePoints'] >= $avil['points']){
    					
//     					Book slot
    					$add->bookedSlots = $add->bookedSlots + $quantity;
    					$add->save();

    					$userScore = ActivityScore::model()->findByPk($avil['facebookId']);    					
    					$userScore->avilablePoints = $userScore->avilablePoints - $avil['points'];
    					$userScore->save();
    					
    					$redeem = new RedeemPoints('create_api');
    					$redeem->redeemFor = $avil['type'];
    					$redeem->entityId = $avil['adId'];
    					$redeem->quantity = $quantity;
    					$redeem->pointsRedeemed = $avil['points'];
    					$redeem->userId = $avil['uId'];
    					$redeem->save();
    					
    					if ($redeem->hasErrors())
    					{
    						throw new Exception(print_r($redeem->getErrors(), true), WS_ERR_UNKNOWN);
    					}
    					
    					
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'slot booked successfully.',
    							'status' => 'OK',
//     							'result' => $avil
    					);
    					
    					
    				}else{
//     					cant book slot
    					$result = $this->error($apiName, WS_ERR_REQUEST_NOT_ACCEPTED, ' Not booked ');
    				}
    				
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