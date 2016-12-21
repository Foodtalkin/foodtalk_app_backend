<?php

class NewsController extends ServiceBaseController
{
    public function actionUpsert()
    {
        $apiName = 'news/Upsert';
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

                	if(isset($_JSON['id']) && !empty ($_JSON['id'])){
                		$news = News::model()->findByPk($_JSON['id']);
                		if(empty($news)){
                			throw new Exception(print_r('Invalid news id', true), WS_ERR_WONG_VALUE);
                		}
                	}
                	else
	                	$news = new News('create_api');
                	
                	if(isset($_JSON['title']) && !empty($_JSON['title']))
                		$news->title = filter_var($_JSON['title'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	 
                	if(isset($_JSON['coverImage']) && !empty($_JSON['coverImage']))
                		$news->coverImage = filter_var($_JSON['coverImage'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	
                	if(isset($_JSON['source']) && !empty($_JSON['source']))
                		$news->source = filter_var($_JSON['source'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	
                	if(isset($_JSON['sourceUrl']) && !empty($_JSON['sourceUrl']))
                		$news->sourceUrl = filter_var($_JSON['sourceUrl'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	
                	if(isset($_JSON['description']) && !empty($_JSON['description']))
                		$news->description = filter_var($_JSON['description'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	
                	if(isset($_JSON['startDate']) && !empty($_JSON['startDate']))
                		$news->startDate = filter_var($_JSON['startDate'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                	
                	if(isset($_JSON['isDisabled'])){
                		$news->isDisabled = $_JSON['isDisabled'];
                	}
                	
                	if(isset($_JSON['disableReason']) && !empty($_JSON['disableReason']))
                		$news->disableReason = filter_var($_JSON['disableReason'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);                	
                	 
                	$news->save();
                	
                	if ($news->hasErrors()){
                		throw new Exception(print_r($news->getErrors(), true), WS_ERR_UNKNOWN);
                	}
                	
                	$result = array(
                			'api' => $apiName,
                			'apiMessage' => 'news updated successfully.',
                			'status' => 'OK',
                			'news' => $news->id
                			
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
    	$apiName = 'news/list';
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
    				$storeOffer = array();
    				$options = array();
    				
    				if(isset($_JSON['page']) && $_JSON['page'])
    					$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    				
    				$status = 'active';
    				
    				if(isset($_JSON['status']) && $_JSON['status'] )
    					$status = filter_var($_JSON['status'], FILTER_SANITIZE_STRING);
    				
    				$news = News::getnews($page, $status, $options);
    				
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Records fetched successfully',
    						'status' => 'OK',
    						'news' => $news
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