<?php
class RestaurantController extends ServiceBaseController
{
    /**
     * Get restaurant profile
     */
    public function actionGetProfile()
    {
        $apiName = 'restaurant/getProfile';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantId']) || empty($_JSON['restaurantId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $restaurantId = filter_var($_JSON['restaurantId'], FILTER_SANITIZE_NUMBER_INT);
                    $restaurant = Restaurant::model()->findByPk($restaurantId);
                    if (is_null($restaurant))
                        $result = $this->error($apiName, WS_ERR_WONG_USER, 'The selected restaurant does not exist.');
                    else
                    {
                        $latitude = 0;
                        $longitude = 0;
                        
                        if(isset($_JSON['latitude']) && $_JSON['latitude'])
                            $latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        
                        if(isset($_JSON['longitude']) && $_JSON['longitude'])
                            $longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        
                        $restaurantProfile = Restaurant::getProfileById($userId, $restaurantId, $latitude, $longitude, true, true);
                        $tipPosts = Post::getTipPostsByRestaurantId($userId, $restaurantId, 15);
                        $imagePosts = Post::getImagePostsByRestaurantId($userId, $restaurantId, 15);
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'restaurantProfile' => $restaurantProfile,
                            'tips' => $tipPosts,
                            'images' => $imagePosts
                        );
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
    
    /**
     * Get tip posts by restaurant id
     */
    public function actionGetTipPosts()
    {
        $apiName = 'restaurant/getTipPosts';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantId']) || empty($_JSON['restaurantId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $restaurantId = filter_var($_JSON['restaurantId'], FILTER_SANITIZE_NUMBER_INT);
                    $restaurant = Restaurant::model()->findByPk($restaurantId);
                    if (is_null($restaurant))
                        $result = $this->error($apiName, WS_ERR_WONG_USER, 'The selected restaurant does not exist.');
                    else
                    {
                        $exceptions = '';   //list of post ids that are not to be included in the list
                    
                        if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                            $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);

                        $tipPosts = Post::getTipPostsByRestaurantId($userId, $restaurantId, 15, $exceptions);
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'tips' => $tipPosts
                        );
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
    
    /**
     * Get image posts by restaurant id
     */
    public function actionGetImagePosts()
    {
        $apiName = 'restaurant/getImagePosts';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantId']) || empty($_JSON['restaurantId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $restaurantId = filter_var($_JSON['restaurantId'], FILTER_SANITIZE_NUMBER_INT);
                    $restaurant = Restaurant::model()->findByPk($restaurantId);
                    if (is_null($restaurant))
                        $result = $this->error($apiName, WS_ERR_WONG_USER, 'The selected restaurant does not exist.');
                    else
                    {
                        $exceptions = '';   //list of post ids that are not to be included in the list
                    
                        if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                            $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        $page = 1;
                        if(isset($_JSON['page']) && $_JSON['page'])
                        	$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
                        
                        $imagePosts = Post::getImagePostsByRestaurantId($userId, $restaurantId, 15, $exceptions, $page);
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'images' => $imagePosts
                        );
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
    
    /**
     * Get restaurant list
     */
    public function actionList()
    {
        $apiName = 'restaurant/list';
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
                    $latitude = 0;
                    $longitude = 0;
                    $includeCuisine = 1;
                    $includeCount = 1;
                    $searchText = '';
                    $recordCount = 0;   //0 means all records
                    $exceptions = '';   //list of restaurant ids that are not to be included in the list
                    $restaurantDistance = 10000;
                    
                    if(isset($_JSON['latitude']) && !empty($_JSON['latitude']))
                        $latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    if(isset($_JSON['longitude']) && !empty($_JSON['longitude']))
                        $longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['searchText']) && !empty($_JSON['searchText'])){
                        $searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
	                    $restaurantDistance=0;
                    }
                    
                    if(isset($_JSON['recordCount']) && $_JSON['recordCount'])
                        $recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                        $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    
                    
                    
//                     restaurants with 1 km only
//                     if(isset($_JSON['restaurantDistance']) && $_JSON['restaurantDistance'])
//                         $restaurantDistance = filter_var($_JSON['restaurantDistance'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    
//                    $sql = Restaurant::getQuery($latitude, $longitude, true);
//                    $sql .= " WHERE r.isDisabled = 0";
//                    
//                    if(isset($_JSON['searchText']) && !empty($_JSON['searchText']))
//                    {
//                        $searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
//                        $sql .= " AND r.restaurantName LIKE '%$searchText%'";
//                    }
//                    
//                    if(isset($_JSON['distance']) && !empty($_JSON['distance']))
//                    {
//                        $distance = filter_var($_JSON['distance'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
//                        $sql .= " HAVING distance <= $distance";
//                    }
//                    
//                    $sql .= " ORDER BY distance";
//                    
//                    $restaurants = Yii::app()->db->createCommand($sql)->queryAll(true);
                    $restaurants = Restaurant::getRestaurants($userId, $latitude, $longitude, $includeCuisine, $includeCount, $searchText, $recordCount, $exceptions, $restaurantDistance);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Records fetched successfully',
                        'status' => 'OK',
                        'restaurants' => $restaurants
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
   	* create resturants
   	*/
    public function actionAdd()
    {
        $apiName = 'restaurant/add';
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
                	$restaurant	 = new Restaurant('create_api');
                	
                    
                    if(isset($_JSON['latitude']) && !empty($_JSON['latitude']))
                        $restaurant->latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    if(isset($_JSON['longitude']) && !empty($_JSON['longitude']))
                        $restaurant->longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['restaurantName']) && !empty($_JSON['restaurantName']))
                        $restaurant->restaurantName = filter_var($_JSON['restaurantName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['area']) && !empty($_JSON['area']))
                    	$restaurant->area = filter_var($_JSON['area'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['address']) && !empty($_JSON['address']))
                    	$restaurant->address = filter_var($_JSON['address'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['city']) && !empty($_JSON['city']))
                    	$restaurant->city = filter_var($_JSON['city'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['role']) && !empty($_JSON['role']))
                    	$restaurant->role = filter_var($_JSON['role'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    

                    $restaurant->isActivated = 0;

                    $restaurant->createId = $userId;
                    
                    //save record
                    $restaurant->save();

//                     $restaurants = Restaurant::getRestaurants($userId, $latitude, $longitude, $includeCuisine, $includeCount, $searchText, $recordCount, $exceptions, $restaurantDistance);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Restaurant added successfully',
                        'status' => 'OK',
                        'restaurantId' => $restaurant->id
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
     * Get restaurant name list
     */
    public function actionListName()
    {
        $apiName = 'restaurant/listName';
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
                    $searchText = '';
                    $recordCount = 0;
                    
                    if(isset($_JSON['searchText']) && !empty($_JSON['searchText']))
                        $searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['recordCount']) && $_JSON['recordCount'])
                        $recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);
                    
                    $restaurants = Restaurant::getRestaurantsName($searchText, $recordCount);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Records fetched successfully',
                        'status' => 'OK',
                        'restaurants' => $restaurants
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
     * Get unique city list
     */
    public function actionCityList()
    {
        $apiName = 'restaurant/cityList';
        $sessionId = null;
        
        try
        {
            $sql = " SELECT DISTINCT city FROM restaurant ORDER BY city";

            $cities = Yii::app()->db->createCommand($sql)->queryAll(true);

            $result = array(
                'api' => $apiName,
                'apiMessage' => 'Records fetched successfully',
                'status' => 'OK',
                'cities' => $cities
            );
        } 
        catch (Exception $e)
        {
            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
}