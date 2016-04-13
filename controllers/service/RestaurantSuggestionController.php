<?php
class RestaurantSuggestionController extends ServiceBaseController
{
    /**
     * Create new restaurantSuggestion
     */
    public function actionAdd()
    {
        $apiName = 'restaurantSuggestion/add';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
        	
        	
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantName']) || empty($_JSON['restaurantName']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant name.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $restaurantSuggestion = new RestaurantSuggestion('create_api');
                    $restaurantSuggestion->userId = $userId;
                    $restaurantSuggestion->restaurantName = filter_var($_JSON['restaurantName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['address']))
                        $restaurantSuggestion->address = filter_var($_JSON['address'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    Yii::app()->db->schema->refresh();
                    
                    if(isset($_JSON['latitude']))
                    	$restaurantSuggestion->latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    if(isset($_JSON['longitude']))
                    	$restaurantSuggestion->longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    
                    
                    if(isset($_JSON['phoneNo']))
                        $restaurantSuggestion->phoneNo = filter_var($_JSON['phoneNo'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    //save record
                    $restaurantSuggestion->save();
                    if ($restaurantSuggestion->hasErrors()) 
                    {
                        throw new Exception(print_r($restaurantSuggestion->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Your suggestion is saved successfully.',
                        'status' => 'OK',
                        'restaurantSuggestionId' => $restaurantSuggestion->id
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
    
    /**
     * List restaurantSuggestions
     */
    public function actionList()
    {
        $apiName = 'restaurantSuggestion/list';
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
                    $restaurantSuggestions = RestaurantSuggestion::getByUserId($userId);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Restaurant suggestions fetched successfully.',
                        'status' => 'OK',
                        'restaurantSuggestions' => $restaurantSuggestions
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