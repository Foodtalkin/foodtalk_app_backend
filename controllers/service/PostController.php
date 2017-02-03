<?php
class PostController extends ServiceBaseController
{
    /**
     * Create new post
     */
    public function actionCreate()
    {
        $apiName = 'post/create';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if((!isset($_JSON['checkedInRestaurantId']) || empty($_JSON['checkedInRestaurantId'])) && (!isset($_JSON['image']) || empty($_JSON['image'])) && (!isset($_JSON['tip']) || empty($_JSON['tip'])))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please add something to post.');
            else if(isset($_JSON['checkedInRestaurantId']) && !empty ($_JSON['checkedInRestaurantId']) && is_null(Restaurant::model()->findByPk($_JSON['checkedInRestaurantId'])))
                $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected restaurant does not exist in our records.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $post = new Post('create_api');
                    
                    if(isset($_JSON['checkedInRestaurantId']) && !empty ($_JSON['checkedInRestaurantId']))
                        $post->checkedInRestaurantId = filter_var($_JSON['checkedInRestaurantId'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['checkedInRestaurantId']) && is_numeric($_JSON['checkedInRestaurantId']) && $_JSON['checkedInRestaurantId']>0){
                    	

                    	
                    	$checkin = Checkins::model()->getCheckin($userId,$_JSON['checkedInRestaurantId']);
// 						$checkin = Checkins::model()->findByAttributes(array('userId'=>$userId,'restaurantId'=>$_JSON['checkedInRestaurantId']), "DATE_FORMAT(createDate,'%m-%d-%Y') = DATE_FORMAT(NOW(),'%m-%d-%Y')");

// 						if(!$checkin){                    	    
// 							$checkin = new Checkins();
// 							$checkin->userId = $userId;
// 							$checkin->restaurantId =  filter_var($_JSON['checkedInRestaurantId'], FILTER_SANITIZE_NUMBER_INT);
							
// 							$checkin->save();
// 							if ($checkin->hasErrors())
// 							{
// 								throw new Exception(print_r($checkin->getErrors(), true), WS_ERR_UNKNOWN);
// 							}								
// 						}                    	
						$post->checkinId = $checkin->id;
                    	                    	
                    }
                    
                    
                    if(isset($_JSON['type']) && !empty ($_JSON['type']))
                    	$post->type = filter_var($_JSON['type'], FILTER_SANITIZE_STRING);
                    
                    
                    if(isset($_JSON['tip']))
                    {
                        $post->tip = trim(mb_convert_encoding( $_JSON['tip'], "UTF-8", "BASE64" ));
                        //$post->tip = base64_decode($_JSON['tip']);
                        //$post->tip = filter_var(base64_decode($_JSON['tip']), FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        //$post->tip = filter_var($_JSON['tip'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    }
                    
                    //save image
                    if(isset($_JSON['image']) && !empty($_JSON['image']))
                    {
                        $imageDir = Yii::getPathOfAlias('webroot.images.post');
                        $thumbDir = Yii::getPathOfAlias('webroot.images.post.thumb');
                        $maxWidth = 320;
                        
//                         echo $img[1];

                        $img = explode('/upload/', $_JSON['image']);

                        if(count($img)>0)
                        {                        	
	                        $post->image = $img[1];
	                        $post->cloud_url = $_JSON['image'];
                        }
                        else 
                        $post->image = saveBase64ImagePng($_JSON['image'], $imageDir, $thumbDir, $maxWidth);
                    }

                    if(isset($_JSON['sendPushNotification']))
                        $post->sendPushNotification = filter_var($_JSON['sendPushNotification'], FILTER_SANITIZE_NUMBER_INT);
                    else
                        $post->sendPushNotification = $user->sendPushNotification;
                    
                    if(isset($_JSON['shareOnFacebook']))
                        $post->shareOnFacebook = filter_var($_JSON['shareOnFacebook'], FILTER_SANITIZE_NUMBER_INT);
                    else
                        $post->shareOnFacebook = $user->shareOnFacebook;
                    
                    if(isset($_JSON['shareOnTwitter']))
                        $post->shareOnTwitter = filter_var($_JSON['shareOnTwitter'], FILTER_SANITIZE_NUMBER_INT);
                    else
                        $post->shareOnTwitter = $user->shareOnTwitter;
                    
                    if(isset($_JSON['shareOnInstagram']))
                        $post->shareOnInstagram = filter_var($_JSON['shareOnInstagram'], FILTER_SANITIZE_NUMBER_INT);
                    else
                        $post->shareOnInstagram = $user->shareOnInstagram;
                    
                    $post->userId = $userId;
                    
                    //save record
                    $post->save();
                    if ($post->hasErrors()) 
                    {
                        throw new Exception(print_r($post->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    
                    //save event
                    Event::saveEvent(Event::POST_CREATED, $userId, $post->id, $post->createDate);
                    
                    
                    $dishReview = new DishReview('create_api');
                    $dishReview->postId = $post->id;
                    
                    if(isset($_JSON['rating']) && $_JSON['rating']>0){
                    	$dishReview->rating = filter_var($_JSON['rating'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    }
                    
                    if(isset($_JSON['dishName'])){
                    	
                    	$_JSON['dishName'] = mb_convert_encoding( $_JSON['dishName'], "UTF-8", "BASE64" );
                    	$dish = Dish::getDishByNmae($_JSON['dishName']);
                    	$dishReview->dishId = $dish->id;
	                    $dishReview->save();
                    }
                    
                    
                    //save hashtags
                    if(!empty($post->tip))
                    {
                        $hashtags = getHashtags($post->tip);
                        foreach($hashtags as $hashtag)
                        {
                            //find if the hashtag exists
                            $tag = Tag::model()->findByAttributes(array('tagName'=>$hashtag));
                            if(is_null($tag))
                            {
                                $tag = new Tag('api_insert');
                                $tag->tagName = $hashtag;
                                $tag->save();
                            }
                            
                            //if the tag exists or created
                            if($tag->id)
                            {
                                //find if the tagmap exists
                                $tagMap = TagMap::model()->findByAttributes(array('tagId'=>$tag->id, 'postId'=>$post->id));
                                if(is_null($tagMap))
                                {
                                    $tagMap = new TagMap('api_insert');
                                    $tagMap->tagId = $tag->id;
                                    $tagMap->postId = $post->id;
                                    $tagMap->save();
                                }
                            }
                        }
                    }
                    
//                    if($post->sendPushNotification)
//                    {
//                        //send notifications to followers
//                        $sql = Follower::getQueryForFollower($userId);
//                        $sql .= " AND (s.deviceToken is not null OR s.deviceToken!='')";
//                        $followers = Yii::app()->db->createCommand($sql)->queryAll(true);
//                        $message = $user->userName . ' has added a new post.';
//                        
//                        foreach($followers as $follower)
//                        {
//                            $session = Session::model()->findByAttributes(array('deviceToken'=>$follower['deviceToken']));
//                            if($session)
//                            {
//                                $session->deviceBadge += 1;
//                                $session->save();
//                                sendApnsNotification($follower['deviceToken'], $message, $follower['deviceBadge']);
//                            }
//                        }
//                    }
                    $fbPostId = new stdClass();
                    if(($post->shareOnFacebook && isset($_JSON['facebookAccessToken'])) || ($post->shareOnTwitter && isset($_JSON['twitterAccessToken']) && isset($_JSON['twitterAccessTokenSecret'])))
                    {
                        $image = empty($post->image) ? '' : Yii::getPathOfAlias('webroot.images.post') . '/' . $post->image;
                        $message = '';
                        
                        if($post->checkedInRestaurantId)
                            $message .= $post->checkedInRestaurant->restaurantName . ' ';
                        
                        if($post->tip)
                            $message .= $post->tip;
                        
                        $aboutFoodtalk = "\nFoodTalk Plus is a visual guide to good food and where to find it. Instead of reviewing restaurants, you can recommend dishes and see what foodtalkers recommend wherever you go.";
                        
                        if($post->shareOnFacebook && isset($_JSON['facebookAccessToken']) && !empty($_JSON['facebookAccessToken']))
                        {
//                            $facebookAccessToken = filter_var($_JSON['facebookAccessToken'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
//                            $name = APP_NAME;
//                            $link = 'http://foodtalkindia.com/';
//                            $fbPostId = PostOnFacebook($facebookAccessToken, $message, $name, $link, $image);
                            
                            $facebookAccessToken = filter_var($_JSON['facebookAccessToken'], FILTER_SANITIZE_STRING);
                            $name = 'FoodTalk';
                            $link = ''; //http://foodtalkindia.com/';
                            $fbPostId = PostOnFacebook($facebookAccessToken, $message, $name, $link, $image);
                        }

                        if($post->shareOnTwitter && isset($_JSON['twitterAccessToken'])  && !empty($_JSON['twitterAccessToken']) && isset($_JSON['twitterAccessTokenSecret']) && !empty($_JSON['twitterAccessTokenSecret']))
                        {
                            $twitterAccessToken = filter_var($_JSON['twitterAccessToken'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                            $twitterAccessTokenSecret = filter_var($_JSON['twitterAccessTokenSecret'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                            PostOnTwitter($twitterAccessToken, $twitterAccessTokenSecret, $message, $image);
                        }
                    }
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Post saved successfully.',
                        'status' => 'OK',
                        'postId' => $post->id,
                        'fbPostId' => $fbPostId
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
     * Create new post
     */
    public function actionDelete()
    {
        $apiName = 'post/delete';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter post id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
                    $post = Post::model()->findByPk($postId);
                    
                    if(is_null($post))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected post does not exist.');
                    else if($post->userId != $user->id)
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You are not allowed to delete this post.');
                    else
                    {
                        $image = $post->image;
                        if($post->delete())
                        {
                        	Checkins::model()->clean($post, 'disabled');
                        	 
                            if($image)
                            {
                                //delete the image file
                                $imageDir = Yii::getPathOfAlias('webroot.images.post');
                                $thumbDir = Yii::getPathOfAlias('webroot.images.post.thumb');

                                @unlink($imageDir . '/' . $image);
                                @unlink($thumbDir . '/' . $image);
                            }
                            
                            $result = array(
                                'api' => $apiName,
                                'apiMessage' => 'Post deleted successfully.',
                                'status' => 'OK',
                                'postId' => $post->id
                            );
                            
                            
                        }
                        else
                        {
                            $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Unable to delete the post. Please try again.');
                        }
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

    
    
    public function actionUpdateRating()
    {
    	$apiName = 'post/updateRating';
    	$sessionId = null;
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter post id.');
    		else
    		{
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
    				$post = Post::model()->findByPk($postId);
    
    				if(is_null($post))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected post does not exist.');
    				else if($post->userId != $user->id)
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You are not allowed to perform this action.');
    				else if(!isset($_JSON['rating']) || empty($_JSON['rating']))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'in valid rating.');
    				else
    				{
    					$dishReview = DishReview::model()->findByAttributes(array('postId'=>$post->id));
    					
    					if($dishReview)
    					{
    						$dishReview->rating = filter_var($_JSON['rating'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
    						$dishReview->save();
    						
    						$result = array(
		    						'api' => $apiName,
		    						'apiMessage' => 'Post successfully rated.',
		    						'status' => 'OK',
		    						'postId' => $post->id
    							);
    					}
    					else
    					{
    						$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Unable to process. Please try again.');
    					}
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
    
    
    public function actionGetUnreated()
    {
    	 
    	$apiName = 'post/getUnreated';
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
//     				$postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
    				$posts = Post::getPost(false, $userId, array('unrated'=>true));
    
    				foreach ($posts as &$post)
//     				{
    					$post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
//     				}
    
//     				$comments = Comment::getCommentsByPostId($postId);
    
//     				foreach ($comments as &$comment)
//     				{
//     					$comment['timeElapsed'] = getTimeElapsed(date_create($comment['createDate']), date_create($comment['currentDate']));

//     				}
    
    
//     				if (count($posts)<1)
//     					$result = $this->error($apiName, WS_ERR_WONG_USER, 'invalid post or is deleted.');
//     				else
//     				{
    							 
    							$result = array(
    									'api' => $apiName,
    									'apiMessage' => 'Records fetched successfully',
    									'status' => 'OK',
    									'posts' => $posts,
//     									'comments' => $comments,
    							);
//     				}
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    	 
    }
    
    
    public function actionGet()
    {
    	
    	$apiName = 'post/get';
    	$sessionId = null;
    	
    	$_JSON = $this->getJsonInput();
    	
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'invalid post or is deleted.');
    		else
    		{
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				
					$options = array();
    				if(isset($_JSON['for']) && $_JSON['for'])
    					$options['for'] = filter_var($_JSON['for'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
    				
    				$postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
    				$posts = Post::getPost($postId, $userId, $options);
    
    				
    				
    				foreach ($posts as &$post)
    				{
    					$post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
// Hasg Tags are removed 
//     					$post['tags'] = Tag::getTagsByPostId($post['id']);
    				}
    				
    				$comments = Comment::getCommentsByPostId($postId);
    				
    				foreach ($comments as &$comment)
    				{
    					$comment['timeElapsed'] = getTimeElapsed(date_create($comment['createDate']), date_create($comment['currentDate']));
    					$comment['userMentioned'] = UserMentioned::getUserByComment($comment['id']);
//     					$post['tags'] = Tag::getTagsByPostId($post['id']);
    				}
    				
    				
    				if (count($posts)<1)
    					$result = $this->error($apiName, WS_ERR_WONG_USER, 'invalid post or is deleted.');
    				else
    				{
//     					$latitude = 0;
//     					$longitude = 0;
    	
//     					if(isset($_JSON['latitude']) && $_JSON['latitude'])
//     						$latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    	
//     					if(isset($_JSON['longitude']) && $_JSON['longitude'])
//     						$longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    	
//     					$restaurantProfile = Restaurant::getProfileById($userId, $restaurantId, $latitude, $longitude, true, true);
//     					$tipPosts = Post::getTipPostsByRestaurantId($userId, $restaurantId, 15);
//     					$imagePosts = Post::getImagePostsByRestaurantId($userId, $restaurantId, 15);
    	
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'Records fetched successfully',
    							'status' => 'OK',
    							'post' => $posts[0],
    							'comments' => $comments,
//     							'images' => $imagePosts
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
     * List posts
     */
    public function actionList()
    {
        $apiName = 'post/list';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['postUserId']) || empty($_JSON['postUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user id whose posts are to be listed.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $postUserId = filter_var($_JSON['postUserId'], FILTER_SANITIZE_NUMBER_INT);
                    $postUser = User::model()->findByPk($postUserId);
                    if (is_null($postUser))
                        $result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected user does not exist.');
                    else
                    {
                        $includeFollowed = false;
                        $includeCount = false;
                        $tagId = 0;
                        $recordCount = 0;   //0 means all records
                        $exceptions = '';   //list of post ids that are not to be included in the list
                        $page=1;
                        if(isset($_JSON['includeFollowed']) && $_JSON['includeFollowed'])
                            $includeFollowed = true;
                        
                        if(isset($_JSON['includeCount']) && $_JSON['includeCount'])
                            $includeCount = true;
                        
                        if(isset($_JSON['recordCount']) && $_JSON['recordCount'])
                            $recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);
                        
                        if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                            $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        
                        if(isset($_JSON['page']) && $_JSON['page'])
                        	$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
                        
                        // tagIds are comma separated tag ids
                        if(isset($_JSON['tagId']) && $_JSON['tagId'])
                            $tagId = filter_var($_JSON['tagId'], FILTER_SANITIZE_NUMBER_INT);
                        
                        $posts = Post::getPostsByUserId($userId, $postUserId, $includeFollowed, $includeCount, $tagId, $recordCount, $exceptions, $page);
                        foreach ($posts as &$post)
                        {
                            $lastComment = Comment::getLastComment($post['id']);
                            if(empty($lastComment))
                                $lastComment = new stdClass();  //blank object
                            else{
                                $lastComment['timeElapsed'] = getTimeElapsed(date_create($lastComment['createDate']), date_create($lastComment['currentDate']));
//                                 $lastComment['commenter'] = '10010010';
                                $lastComment['userMentioned'] = UserMentioned::getUserByComment($lastComment['id']);                            	
                            }
                            
                            $post['lastComment'] = $lastComment;
                            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
                            $post['tags'] = Tag::getTagsByPostId($post['id']);
                        }
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Posts fetched successfully.',
                            'status' => 'OK',
                            'posts' => $posts,
                            'deviceBadge' => Session::getDeviceBadge($userId)
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
     * List posts having an image and checkin ordered by restaurant distance from current location
     */
    public function actionGetImageCheckInPosts()
    {
        $apiName = 'post/getImageCheckInPosts';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput('get');
        
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
                    $recordCount = 0;   //0 means all records
                    $exceptions = '';   //list of post ids that are not to be included in the list
                    $tagId = 0;
                    $search='';
                    $dishId = '';
                    $page = 1; 
                    $region ='';
                    
                    if(isset($_JSON['latitude']) && $_JSON['latitude'])
                        $latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['longitude']) && $_JSON['longitude'])
                        $longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['recordCount']) && $_JSON['recordCount'])
                        $recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);

                    
                    if(isset($_JSON['dishId']) && $_JSON['dishId'])
                    	$dishId = filter_var($_JSON['dishId'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    elseif(isset($_JSON['search']) && $_JSON['search'])
                    	$search = filter_var($_JSON['search'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                        $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['tagId']) && $_JSON['tagId'])
                        $tagId = filter_var($_JSON['tagId'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['page']) && $_JSON['page'])
                    	$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['region']) && $_JSON['region'])
                    	$region = filter_var($_JSON['region'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    
                    $posts = 
//                     Post::getDiscoverPosts($userId, $latitude, $longitude, $tagId, $recordCount, $exceptions);
                    Post::getDiscoverPosts($userId, $latitude, $longitude, $tagId, $recordCount, $exceptions,0, $search, $page, 0, $dishId, $region);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Posts fetched successfully.',
                        'status' => 'OK',
                        'posts' => $posts
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
     * List checkin posts with images and without images in two different arrays
     */
    public function actionGetCheckInPosts()
    {
        $apiName = 'post/getCheckInPosts';
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
                    $recordCount = 0;   //0 means all records
                    $exceptions = '';   //list of post ids that are not to be included in the list
                    $tagId = 0;
                    $search='';
                    $page =1;
                    
                    if(isset($_JSON['latitude']) && $_JSON['latitude'])
                        $latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['longitude']) && $_JSON['longitude'])
                        $longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['recordCount']) && $_JSON['recordCount'])
                        $recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
                        $exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['search']) && $_JSON['search'])
                    	$search = filter_var($_JSON['search'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    if(isset($_JSON['page']) && $_JSON['page'])
                    	$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
                    
                    if(isset($_JSON['tagId']) && $_JSON['tagId'])
                        $tagId = filter_var($_JSON['tagId'], FILTER_SANITIZE_NUMBER_INT);
                    
                    $imagePosts = Post::getDiscoverPosts($userId, $latitude, $longitude, $tagId, $recordCount, $exceptions,0, $search, $page);
                    $checkinPosts = Post::getCheckInPosts($userId, $latitude, $longitude, $tagId, $recordCount, $exceptions);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Posts fetched successfully.',
                        'status' => 'OK',
                        'imagePosts' => $imagePosts,
                        'checkinPosts' => $checkinPosts
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