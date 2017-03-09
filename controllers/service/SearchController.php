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

		$searchText = trim($searchText);
		$searchText = trim($searchText, ',');
		$searchText = strtolower($searchText);
		
		$searchArr = split(',', $searchText, 2);
		$search = array();
		
		switch ($entity){
			case 'restaurant':
				
				$indexType = '/restaurant';
				
				if(isset($searchArr[1])){
						
					$search['query']['bool']['must'][] = array( 'match'=> [ 'restaurantname'=>$searchArr[0] ] );
						
					$searchWords = explode(' ', $searchArr[1]);
						
					$search['query']['bool']['should'][] = array('wildcard'=> [ 'address'=> end($searchWords).'*' ] );
					$search['query']['bool']['should'][] = array('match'=> [ 'address'=> $searchArr[1] ] );
				
				}else{
						
					$searchWords = explode(' ', $searchArr[0]);
						
					if(trim(end($searchWords)) == trim($searchArr[0]))
						$search['query']['bool']['must'][] = array('wildcard'=> [ 'restaurantname'=> end($searchWords).'*' ] );
					else{
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'restaurantname'=> end($searchWords).'*' ] );
						$search['query']['bool']['must'][] = array('match'=> [ 'restaurantname'=> $searchArr[0] ] );
					}
				}
				
				if(isset($options['isactivated']) and $options['isactivated'])
				$search['query']['bool']['must'][] = array('match'=> [ 'isactivated'=> true ] );
				
				break;
			
			case 'user':
				$indexType = '/user';
				
					$searchWords = explode(' ', $searchText);
				
					if(count($searchWords) == 1){
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'username'=> end($searchWords).'*' ] );
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'fullname'=> end($searchWords).'*' ] );
					}
					else{
						
// 						match users with all characters joined togather
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'username'=> implode('', $searchWords).'*' ] );
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'fullname'=> implode('', $searchWords).'*' ] );
						
// 						matches end string 
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'username'=> end($searchWords).'*' ] );
						$search['query']['bool']['should'][] = array('wildcard'=> [ 'fullname'=> end($searchWords).'*' ] );
						
						
						$search['query']['bool']['should'][] = array('match'=> [ 'username'=> $searchText ] );
						$search['query']['bool']['should'][] = array('match'=> [ 'fullname'=> $searchText ] );
						
// 						$search['query']['bool']['should'][] = array('wildcard'=> [ 'cityname'=> end($searchWords).'*' ] );
// 						$search['query']['bool']['should'][] = array('wildcard'=> [ 'cityname'=> end($searchWords).'*' ] );
						
					}
					
					if(isset($options['excludeusers']) and !empty($options['excludeusers']))
					$search['query']['bool']['must_not'][] = array('terms'=> [ 'id'=> $options['excludeusers'] ] );
						
				break;
				
			case 'dish':
				$indexType = '/dish';
				
				$searchWords = explode(' ', $searchText);
				
				$search['query']['bool']['should'][] = array('wildcard'=> [ 'dishname'=> implode('', $searchWords).'*' ] );
				$search['query']['bool']['should'][] = array('wildcard'=> [ 'dishname'=> end($searchWords).'*' ] );
				$search['query']['bool']['should'][] = array('match'=> [ 'dishname'=> $searchText ] );
				
				
				break;
			default:
				$indexType = '';
				$searchWords = explode(' ', $searchText);
						$query = '{ "query": { "query_string": { "query": "'.
							end($searchWords).'* '.
							$searchText.
						'", "analyze_wildcard": true } } }';
						
						$search = json_decode($query, true);
			
		}
				
		

		
		$search["track_scores"] = true;
		
		$search['sort'][] = array( "_score" => "desc" );		
		
		if(isset($options['location']) and !empty($options['location'])){
			
			$search['sort'][] = array("_geo_distance" => array(
					"location" => $options['location']['lat']. ', '.$options['location']['lon'] ,
					"order" => "asc",
					"unit" => "km"
					
			));
		}
		
// 		$search['sort'][] = array("popularity" => "desc");

// 		{
// 			"_geo_distance" : {
// 			"location" : "12.9306888889,77.6135027778",
// 			"order" : "asc",
// 			"unit" : "km"
// 			}
// 		}
		
		
		$searchurl = '/foodtalkindex'.$indexType.'/_search';
// 		$query = '{ "query": { "query_string": { "query": "'.
// 			end($searchWords).'* '.
// 			$searchText.
// 		'", "analyze_wildcard": true } } }';
		

		$query = json_encode($search);
// 		echo $query;
// 		die();	

// 		return array('Finalresult'=>es($query, $searchurl), 'query' => $search, 'options'=>$options);
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
	
	public function  actionDelete(){
		
		
// 		$dd = es('', '/foodtalkindex/restaurant/46450' ,'DELETE');
// 		var_dump($dd);
		echo 'TEST';
		
	}
	
	public function actionindex(){
		
		$this->actionEs('Search');
	}
	
		
    public function actionEs($apiName = 'Search/es')
    {
        
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
//                 	if(isset($_JSON['searchText']) && strlen(trim($_JSON['searchText'])) > 1){
                	if(isset($_JSON['searchText']) && !empty($_JSON['searchText']))
                		 
                		$searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING);
					else 
						$searchText ='';
                		$options = array();
                		
                		if(isset($_JSON['latitude']) && !empty($_JSON['latitude']))
                			$options['location']['lat'] = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                		
                		if(isset($_JSON['longitude']) && !empty($_JSON['longitude']))
                			$options['location']['lon'] = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                		
                		if(isset($_JSON['isactivated']) && !empty($_JSON['isactivated']))
                			$options['isactivated'] = true ;
                		
                		$usersBlockedHim = BlockUser::model()->findAllByAttributes(array('blockedUserId' => $userId));
                		$usersHeBlocked = BlockUser::model()->findAllByAttributes(array('userId' => $userId));
                		
                		foreach ($usersBlockedHim as $users){
                			$options['excludeusers'][] = $users->userId; 
                		}
                		
                		foreach ($usersHeBlocked as $users){
                			$options['excludeusers'][] = $users->blockedUserId;
                		}
                		
                		if(isset($options['excludeusers']) and !empty($options['excludeusers']))
	                		$options['excludeusers'] = array_unique($options['excludeusers']);
                		
//                 		$options['excludeusers'] = [12];
                		
                		
                		$type='';
                		if(isset($_JSON['type']))
                			$type = filter_var($_JSON['type'], FILTER_SANITIZE_STRING);
                		 
                		$result = self::search($searchText, $type, $options);
                		
                		$result = array(
                				'api' => $apiName,
                				'apiMessage' => 'Tags fetched successfully.',
                				'status' => 'OK',
                				'result' => $result
                		);
//                 	}
//                 	else 
//                 		$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input for search.');
                	
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