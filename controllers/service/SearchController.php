<?php

class SearchController extends ServiceBaseController
{
    /**
     * List tags
     */
	
	private function search($searchText , $entity = '', $options=array()){

		$curl = curl_init();
		$elasticUrl = 'http://localhost:9200';
		$port = '9200';
		
		switch ($entity){
			case 'restaurant':
				$indexType = '/restaurant';
				break;
			
			case 'user':
				$indexType = '/user';
				break;
				
			case 'dish':
				$indexType = '/dish';
				break;
			default:
				$indexType = '';
			
		}
		
		$searchurl = '/foodtalkindex'.$indexType.'/_search';
		$query = '{ "query": { "query_string": { "query": "'
// 				.$searchText.'* '
						.$searchText.'", "analyze_wildcard": true } } }';
		 
		return es($query, $searchurl);
	}
	
	private function esGET(){
	
		
		
	}
	
	private function esPUT(){
		
	}
	
	private function esPOST(){
		
	}
	
	private function esDELETE(){
	
	}
		
    public function actionEs()
    {
        $apiName = 'Search/es';
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
                	
                	$searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING);
                	 
                	$type = filter_var($_JSON['type'], FILTER_SANITIZE_STRING);
                	
                	$result = self::search($searchText, $type);
                             	
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