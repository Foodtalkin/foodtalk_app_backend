<?php

class BookmarkController extends ServiceBaseController
{
    public function actionAdd()
    {
        $apiName = 'bookmark/add';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter postId.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                	
                	
                	$postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
                	
                    $post  = Post::model()->findByPk($postId);
                    if (is_null($post))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'No such post');
                    else
                    {
                        $oldFavourite = Bookmark::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                        if(!is_null($oldFavourite))
                            $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have already marked this post as favourite.');
                        else
                        {
                            $favourite = new Bookmark('add_api');
                            $favourite->userId = $userId;
                            $favourite->postId = $postId;
                            
                            //save record
                            $favourite->save();
                            if ($favourite->hasErrors()) 
                            {
                                throw new Exception(print_r($favourite->getErrors(), true), WS_ERR_UNKNOWN);
                            }
                            
                            //save event
                            Event::saveEvent(11, $userId, $postId, $favourite->createDate, $post->userId );

                            //send notifications to followers of the user
//                            $sql = Follower::getQueryForFollower($userId);
//                            $sql .= " AND (s.deviceToken is not null OR s.deviceToken!='')";
//                            $followers = Yii::app()->db->createCommand($sql)->queryAll(true);
//                            $message = $user->userName . ' has marked a restaurant as favourite.';
//
//                            foreach($followers as $follower)
//                            {
//                                $session = Session::model()->findByAttributes(array('deviceToken'=>$follower['deviceToken']));
//                                if($session)
//                                {
//                                    $session->deviceBadge += 1;
//                                    $session->save();
//                                    sendApnsNotification($follower['deviceToken'], $message, $follower['deviceBadge']);
//                                }
//                            }

                            $result = array(
                                'api' => $apiName,
                                'apiMessage' => 'Your favourite marked successfully.',
                                'status' => 'OK',
                                'favouriteId' => $favourite->id
                            );
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
    
    /**
     * Remove favourite from a post
     */
    public function actionDelete()
    {
        $apiName = 'bookmark/delete';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'postId.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                
                
                
                $postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
                $post = Post::model()->findByPk($postId);
                    
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
//                 else if (is_null($restaurant))
//                     $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected restaurant does not exist.');
                else
                {
                    $favourite = Bookmark::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                    if(is_null($favourite))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have not marked this Post as favourite yet.');
                    else
                    {
                        $favourite->delete();
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Your favourite removed successfully.',
                            'status' => 'OK'
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
    
    public function actionlist()
    {
    	$apiName = 'bookmark/list';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
//     		else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
//     			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'postId.');
    		else
    		{
    			
    			$recordCount = 15;   //0 means all records
    			$exceptions = '';   //list of post ids that are not to be included in the list
    			$page=1;
    			
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    
    
    			if(isset($_JSON['recordCount']) && $_JSON['recordCount'])
    				$recordCount = filter_var($_JSON['recordCount'], FILTER_SANITIZE_NUMBER_INT);
    			
    			if(isset($_JSON['exceptions']) && $_JSON['exceptions'])
    				$exceptions = filter_var($_JSON['exceptions'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
    			
    			if(isset($_JSON['page']) && $_JSON['page'])
    				$page = filter_var($_JSON['page'], FILTER_SANITIZE_NUMBER_INT);
    
//     			$postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
//     			$post = Post::model()->findByPk($postId);
    
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			//                 else if (is_null($restaurant))
    				//                     $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected restaurant does not exist.');
    			else
    			{
    				$favourite = Bookmark::getBookmarkDish($userId, $page, $recordCount);
    				if(is_null($favourite))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have not marked this Post as favourite yet.');
    				else
    				{
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'Your favourite fetched successfully.',
    							'status' => 'OK',
    							'dish' => $favourite
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
    
    
    public function actionListByPost()
    {
    	$apiName = 'bookmark/listByPost';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter post id whose likes are to be listed.');
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
    				if (is_null($post))
    					$result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected post does not exist.');
    				else
    				{
    					$imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
    					$thumbPath = Yii::app()->getBaseUrl(true) . '/images/user/thumb/';
    
    					$sql = Bookmark::getQuery($postId, $userId);
    					$bookmark = Yii::app()->db->createCommand($sql)->queryAll(true);
    
    					$result = array(
    							'api' => $apiName,
    							'apiMessage' => 'bookmark fetched successfully.',
    							'status' => 'OK',
    							'users' => $bookmark
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
    
    
}