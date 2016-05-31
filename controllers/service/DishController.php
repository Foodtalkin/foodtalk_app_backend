<?php
class DishController extends ServiceBaseController
{
    /**
     * List tags
     */
    public function actionSearch()
    {
        $apiName = 'dish/search';
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
                    $search = '';
                    if(isset($_JSON['search']) && $_JSON['search'])
                        $search = filter_var($_JSON['search'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    
                    $region = '';
                    if(isset($_JSON['region']) && $_JSON['region'])
                    	$region = filter_var($_JSON['region'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    

                    $result = Dish::listByName($search, $region);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Tags fetched successfully.',
                        'status' => 'OK',
                        'result' => $result
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
    	$apiName = 'dish/list';
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
    				
    				$id = 0;
    				if(isset($_JSON['id']) && $_JSON['id'])
    					$id = filter_var($_JSON['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
    
    				$result = Dish::listAll(array('id'=>$id));
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Tags fetched successfully.',
    						'status' => 'OK',
    						'result' => $result
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