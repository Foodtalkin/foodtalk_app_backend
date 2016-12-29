<?php
class UserController extends ServiceBaseController
{
    /**
     * Get user profile
     */
    public function actionGetProfile()
    {
        $apiName = 'user/getProfile';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
//         error_log('LOG : '. print_r( $_JSON, true ) );
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter selected user id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
//                     $selectedUserId = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    
                    $selectedUser = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    
                    
                    if(is_numeric($selectedUser))
                    	$selectedUserId = $selectedUser;
                    //                     	$user = User::model()->findByAttributes(array('userName'=>$_JSON['userName']));
                    else {
                    	$selected_user = User::model()->findByAttributes(array('userName'=>$selectedUser));
                    	$selectedUserId = $selected_user['id'];
                    }
                    
                    
                    $profile = User::getProfileById($userId, $selectedUserId);
                    
                    if(is_null($profile) || empty($profile))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
                    else
                    {
                        $tipPosts = Post::getTipPostsByUserId($userId, $selectedUserId, 12);
                        
                        foreach($tipPosts as &$tipPost)
                        {
                            $tipPost['tags'] = Tag::getTagsByPostId($tipPost['id']);
                        }
                        
                        $latitude = 0;
                        $longitude = 0;
                        
                        if(isset($_JSON['latitude']) && $_JSON['latitude'])
                        	$latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        
                        if(isset($_JSON['longitude']) && $_JSON['longitude'])
                        	$longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        
                        
                        
                        $imagePosts = Post::getImagePostsByUserId($userId, $selectedUserId, 12, '', 1, $latitude, $longitude);
//                         $checkInPosts = Post::getUniqueCheckInPostsByUserId($userId, $selectedUserId, 12);
                        $favourites = Favourite::getFavouriteRestaurants($selectedUserId);
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'profile' => $profile,
                            'tipPosts' => $tipPosts,
                            'imagePosts' => $imagePosts,
//                             'checkInPosts' => $checkInPosts,
                            'favourites' => $favourites
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
    
    
    
    public function actionProfile()
    {
    	$apiName = 'user/Profile';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    	//         error_log('LOG : '. print_r( $_JSON, true ) );
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['userName']) || empty($_JSON['userName']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter userName.');
    		else
    		{
    			$user = User::model()->findByAttributes(array('userName'=>$_JSON['userName']));
    			
    			
    			
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$selectedUserId = $user->id; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
    				$userId = $user->id;
    				    				
    				$profile = User::getProfileById($userId, $selectedUserId);
    
    				if(is_null($profile) || empty($profile))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
    				else
    				{
    					$tipPosts = Post::getTipPostsByUserId($userId, $selectedUserId, 12);
    
    					foreach($tipPosts as &$tipPost)
    					{
    						$tipPost['tags'] = Tag::getTagsByPostId($tipPost['id']);
    					}
    
    					$latitude = 0;
    					$longitude = 0;    
    
    					$imagePosts = Post::getImagePostsByUserId($userId, $selectedUserId, 12, '', 1, $latitude, $longitude);
    					//                         $checkInPosts = Post::getUniqueCheckInPostsByUserId($userId, $selectedUserId, 12);
    					$favourites = Favourite::getFavouriteRestaurants($selectedUserId);
    
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'Records fetched successfully',
    							'status' => 'OK',
    							'profile' => $profile,
    							'tipPosts' => $tipPosts,
    							'imagePosts' => $imagePosts,
    							'favourites' => $favourites
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
     * Get tip post by user id
     */
    public function actionGetTipPosts()
    {
        $apiName = 'user/getTipPosts';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter selected user id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $selectedUserId = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    $selectedUser = User::model()->findByPk($selectedUserId);
                    
                    if(is_null($selectedUser))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
                    else
                    {
                        $exceptions = '';   //list of post ids that are not to be included in the list
                    
                        if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                            $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);

                        $tipPosts = Post::getTipPostsByUserId($userId, $selectedUserId, 12, $exceptions);
                        
                        foreach($tipPosts as &$tipPost)
                        {
                            $tipPost['tags'] = Tag::getTagsByPostId($tipPost['id']);
                        }
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'tipPosts' => $tipPosts
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
     * Get image post by user id
     */
    public function actionImagePosts()
    {
    	$apiName = 'user/ImagePosts';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['userName']) || empty($_JSON['userName']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter userName.');
    		else
    		{
    			$user = User::model()->findByAttributes(array('userName'=>$_JSON['userName']));
    			
    			
    			
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$selectedUserId = $user->id; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
    				$userId = $user->id;
    
    				if(is_null($user))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
    				else
    				{
    					$page =1;
    					$exceptions = '';   //list of post ids that are not to be included in the list
    
    					if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
    						$exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
    
    					if(isset($_JSON['page']) && $_JSON['page'])
    						$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    
    					$imagePosts = Post::getImagePostsByUserId($userId, $selectedUserId, 12, $exceptions, $page);
    
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'Records fetched successfully',
    							'status' => 'OK',
    							'imagePosts' => $imagePosts
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
     * Get image post by user id
     */
    public function actionGetImagePosts()
    {
        $apiName = 'user/getImagePosts';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter selected user id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $selectedUserId = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    
//                     $selectedUser = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    
                    
                    if(is_numeric($selectedUserId))
	                    $selectedUser = User::model()->findByPk($selectedUserId);
                    else {
                    	$selectedUser = User::model()->findByAttributes(array('userName'=>$selectedUserId));
                    	$selectedUserId = $selectedUser['id'];
                    }
                    
                    if(is_null($selectedUser))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
                    else
                    {
                    	$page =1;
                        $exceptions = '';   //list of post ids that are not to be included in the list
                    
                        if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                            $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);

                        if(isset($_JSON['page']) && $_JSON['page'])
                        	$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
                        
                        $imagePosts = Post::getImagePostsByUserId($userId, $selectedUserId, 12, $exceptions, $page);
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'imagePosts' => $imagePosts
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
     * Get check-in post by user id
     */
    public function actionGetCheckInPosts()
    {
        $apiName = 'user/getCheckInPosts';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter selected user id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $selectedUserId = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    $selectedUser = User::model()->findByPk($selectedUserId);
                    
                    if(is_null($selectedUser))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
                    else
                    {
                        $exceptions = '';   //list of post ids that are not to be included in the list
                    
                        if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                            $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);

                        $checkInPosts = Post::getUniqueCheckInPostsByUserId($userId, $selectedUserId, 12, $exceptions);
                        $favourites = Favourite::getFavouriteRestaurants($selectedUserId);
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Records fetched successfully',
                            'status' => 'OK',
                            'checkInPosts' => $checkInPosts
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
    
    public function actionGetCheckIn()
    {
    	$apiName = 'user/getCheckIn';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter selected user id.');
    		else
    		{
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			$page = 1;
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$selectedUserId = $_JSON['selectedUserId']; //filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
    				
    				

    				if(is_numeric($selectedUserId))
	    				$selectedUser = User::model()->findByPk($selectedUserId);
    				//                     	$user = User::model()->findByAttributes(array('userName'=>$_JSON['userName']));
    				else {
    					$selectedUser = User::model()->findByAttributes(array('userName'=>$selectedUser));
    					$selectedUserId = $selectedUser['id'];
    				}
    				
    
    				if(is_null($selectedUser))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exist.');
    				else
    				{
    					$exceptions = '';   //list of post ids that are not to be included in the list
    
    					if(isset($_JSON['page']) && $_JSON['page'])
    						$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    					
    
    					$checkInPosts = Post::getUniqueCheckInCount($selectedUserId, $page);
//     					$favourites = Favourite::getFavouriteRestaurants($selectedUserId);
    
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'Records fetched successfully',
    							'status' => 'OK',
    							'checkIn' => $checkInPosts
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
     * Update user name only
     */
    public function actionUpdateUserName()
    {
        $apiName = 'user/updateUserName';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['userName']) || empty($_JSON['userName']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user name.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                $userName = filter_var($_JSON['userName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                $oldUserByName = User::model()->findByAttributes(array('userName'=>$userName));
                
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else if (!is_null($oldUserByName))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'This user name is already taken. Please try again.');
                else
                {
                    $user->userName = $userName;
                    
                    //save record
                    $user->save();
                    if ($user->hasErrors()) 
                    {
                        throw new Exception(print_r($user->getErrors(), true), WS_ERR_UNKNOWN);
                    }

                    $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                    $thumbPath = $imagePath . 'thumb/';
                    
                    $sql = User::getQuery($imagePath, $thumbPath);
                    $sql .= " WHERE u.id = $userId";
                    $profile = Yii::app()->db->createCommand($sql)->queryRow(true);
                    
                    //add followed users by facebookId
                    if(isset($_JSON['facebookIds']) && !empty($_JSON['facebookIds']))
                    {
                        $facebookIdCsv = filter_var($_JSON['facebookIds'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        $facebookIds = explode(',', $facebookIdCsv);
                        foreach ($facebookIds as $facebookId)
                        {
                            $followedUser = User::model()->findByAttributes(array('facebookId'=>$facebookId));
                            
                            if(!is_null($followedUser) && $userId != $followedUser->id)
                            {
                                $follower = Follower::model()->findByAttributes(array('followerUserId'=>$userId, 'followedUserId'=>$followedUser->id));
                                if(is_null($follower))
                                {
                                    $follower = new Follower('follow_api');
                                    $follower->followerUserId = $userId;
                                    $follower->followedUserId = $followedUser->id;

                                    //save record
                                    $follower->save();

                                    //save event
                                    Event::saveEvent(Event::USER_FOLLOWED, $userId, $follower->id, $follower->createDate, $followedUser->id);
                                }
                            }
                        }
                    }

                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User name updated successfully.',
                        'status' => 'OK',
                        'profile' => $profile
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
     * Update user profile
     */
    public function actionUpdateProfile()
    {
        $apiName = 'user/updateProfile';
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
                    if(isset($_JSON['userName']))
                        $user->userName = filter_var($_JSON['userName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['fullName']))
                        $user->fullName = filter_var($_JSON['fullName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['email']))
                        $user->email = filter_var($_JSON['email'], FILTER_SANITIZE_EMAIL);
                    
                    if(isset($_JSON['city']))
                        $user->city = filter_var($_JSON['city'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['latitude']))
                        $user->latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['longitude']))
                        $user->longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    //save image
                    if(isset($_JSON['image']) && !empty($_JSON['image']))
                    {
                        $imageDir = Yii::getPathOfAlias('webroot.images.user');
                        $thumbDir = Yii::getPathOfAlias('webroot.images.user.thumb');
                        $maxWidth = 72;
                        $user->image = saveBase64ImagePng($_JSON['image'], $imageDir, $thumbDir, $maxWidth);
                    }

                    //save record
                    $user->save();
                    if ($user->hasErrors()) 
                    {
                        throw new Exception(print_r($user->getErrors(), true), WS_ERR_UNKNOWN);
                    }

                    $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                    $thumbPath = $imagePath . 'thumb/';
                    
                    $sql = User::getQuery($imagePath, $thumbPath);
                    $sql .= " WHERE u.id = $userId";
                    $profile = Yii::app()->db->createCommand($sql)->queryRow(true);

                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User profile updated successfully.',
                        'status' => 'OK',
                        'profile' => $profile
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
     * Update user settings
     */
    public function actionUpdateSetting()
    {
        $apiName = 'user/updateSetting';
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
                    if(isset($_JSON['sendPushNotification']))
                        $user->sendPushNotification = filter_var($_JSON['sendPushNotification'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['shareOnFacebook']))
                        $user->shareOnFacebook = filter_var($_JSON['shareOnFacebook'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['shareOnTwitter']))
                        $user->shareOnTwitter = filter_var($_JSON['shareOnTwitter'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['shareOnInstagram']))
                        $user->shareOnInstagram = filter_var($_JSON['shareOnInstagram'], FILTER_SANITIZE_NUMBER_INT);
                    
                    //save record
                    $user->save();
                    if ($user->hasErrors()) 
                    {
                        throw new Exception(print_r($user->getErrors(), true), WS_ERR_UNKNOWN);
                    }

                    $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                    $thumbPath = $imagePath . 'thumb/';
                    
                    $sql = User::getQuery($imagePath, $thumbPath);
                    $sql .= " WHERE u.id = $userId";
                    $profile = Yii::app()->db->createCommand($sql)->queryRow(true);

                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User settings updated successfully.',
                        'status' => 'OK',
                        'profile' => $profile
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
     * Update user cuisine
     */
    public function actionUpdateCuisine()
    {
        $apiName = 'user/updateCuisine';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['cuisines']) || empty($_JSON['cuisines']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select cuisine.');
            else if(count($_JSON['cuisines'])<1)
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select atleast one cuisine.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    //delete old cuisines
                    UserCuisine::model()->deleteAllByAttributes(array('userId'=>$userId));
                    
                    //insert new cuisines
                    $cuisines = $_JSON['cuisines'];
                    foreach($cuisines as $cuisine)
                    {
                        $userCuisine = new UserCuisine('add_api');
                        $userCuisine->userId = $user->id;
                        $userCuisine->cuisineId = $cuisine;
                        $userCuisine->save();
                    }

                    $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                    $thumbPath = $imagePath . 'thumb/';
                    
                    $sql = User::getQuery($imagePath, $thumbPath, true);
                    $sql .= " WHERE u.id = $userId";
                    $profile = Yii::app()->db->createCommand($sql)->queryRow(true);

                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User cuisine updated successfully.',
                        'status' => 'OK',
                        'profile' => $profile
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
     * Delete user account permanently
     */
    public function actionDelete()
    {
        $apiName = 'user/delete';
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
                    //delete record
                    if (!$user->delete()) 
                    {
                        throw new Exception(print_r($user->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    Checkins::model()->clean($user, 'disabled');
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User record deleted successfully.',
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
    
    /**
     * Get user list
     */
    public function actionList()
    {
        $apiName = 'user/list';
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
                    $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                    $thumbPath = $imagePath . 'thumb/';
                    
                    $sql = User::getQuery($imagePath, $thumbPath, true);
                    $sql .= " WHERE u.isDisabled = 0";
                    
                    if(isset($_JSON['searchText']) && !empty($_JSON['searchText']))
                    {
                        $searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        $sql .= " AND (u.userName LIKE '%$searchText%' OR u.fullName LIKE '%$searchText%')";
                    }
                    
                    $users = Yii::app()->db->createCommand($sql)->queryAll(true);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Records fetched successfully',
                        'status' => 'OK',
                        'users' => $users
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
     * Get user names list, used for search and auto suggest
     */
    public function actionListNames()
    {
        $apiName = 'user/listNames';
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
                    if(isset($_JSON['searchText']) && !empty($_JSON['searchText']))
                        $searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['search']) && !empty($_JSON['search']))
                    	$searchText = filter_var($_JSON['search'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    $users = User::getUserNames($searchText);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Records fetched successfully',
                        'status' => 'OK',
                        'users' => $users
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
     * Get suggested users to follow
     */
    public function actionSuggestions()
    {
        $apiName = 'user/suggestions';
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
                    $exceptions = '';
                    $recordCount = 12;
                    
                    if(isset($_JSON['searchText']) && !empty($_JSON['searchText']))
                        $searchText = filter_var($_JSON['searchText'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['exceptions']) && !empty($_JSON['exceptions']))
                        $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['recordCount']) && !empty($_JSON['recordCount']))
                        $recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);
                    
                    $users = User::getSuggestedFollows($userId, $searchText, $recordCount, $exceptions);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Records fetched successfully',
                        'status' => 'OK',
                        'users' => $users
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
    
    
    public function actionReferrals()
    {
    	$apiName = 'user/referrals';
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
    				$users = User::getReferral($userId);
    
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Records fetched successfully',
    						'status' => 'OK',
    						'users' => $users
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
     * Log out of application
     */
    public function actionLogout()
    {
        $apiName = 'user/logout';
        $sessionId = null;
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, 99, 'Please enter session id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    //Log-out user from all devices by deleting sessionId
                    Session::model()->deleteAllByAttributes(array('userId' => $userId));
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Logout successful',
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
    
    /**
     * Reset badge
     */
    public function actionResetBadge()
    {
        $apiName = 'user/resetBadge';
        $sessionId = null;
        $_JSON = $this->getJsonInput();
        
        try 
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, 99, 'Please enter session id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $session = Session::model()->findByAttributes(array('userId'=>$userId));
                    $session->scenario = 'updateBadge';
                    $session->deviceBadge = 0;
                    
                    //save record
                    $session->save();
                    if($session->hasErrors()) 
                    {
                        throw new Exception(print_r($session->getErrors(), true), WS_ERR_UNKNOWN);
                    }

                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Badge reset successfully',
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
    
    /**
     * Check existance of user name
     */
    public function actionNameExists()
    {
        $apiName = 'user/nameExists';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON['userName']) || empty($_JSON['userName']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user name.');
            else
            {
                $userName = filter_var($_JSON['userName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                $user = User::model()->findByAttributes(array('userName'=>$userName));
                $result = array(
                    'api' => $apiName,
                    'apiMessage' => 'User name checked successfully.',
                    'status' => 'OK',
                    'exists' => !is_null($user)
                );
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Get user records by facebook ids
     */
    public function actionGetUsersByFacebookIds()
    {
        $apiName = 'user/getUsersByFacebookIds';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['facebookIds']) || empty($_JSON['facebookIds']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter facebook ids.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $facebookIds = filter_var($_JSON['facebookIds'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    $users = User::getUsersByFacebookIds($userId, $facebookIds);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Records fetched successfully',
                        'status' => 'OK',
                        'users' => $users
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
     * Check existance of userName
     */
//    public function actionCheckExistance()
//    {
//        $apiName = 'auth/checkExistance';
//        $nameExists = 1;
//        
//        $_JSON = $this->getJsonInput();
//        
//        try
//        {
//            if(!isset($_JSON) || empty($_JSON))
//                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
//            else if(!isset($_JSON['userName']) || empty($_JSON['userName']))
//                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user name.');
//            else
//            {
//                $userName = filter_var($_JSON['userName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
//                $user = User::model()->findByAttributes(array('userName' => $userName));
//                
//                if(is_null($user) || empty($user))
//                    $nameExists = 0;
//                
//                $result = array(
//                    'api' => $apiName,
//                    'apiMessage' => 'User record checked successfully.',
//                    'status' => 'OK',
//                    'nameExists' => $nameExists
//                );
//            }
//        } 
//        catch (Exception $e) 
//        {
//            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
//        }
//        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
//    }
}