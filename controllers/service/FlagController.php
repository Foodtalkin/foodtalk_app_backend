<?php
class FlagController extends ServiceBaseController
{
    /**
     * Add flag to a post
     */
    public function actionAdd()
    {
        $apiName = 'flag/add';
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
                    if (is_null($post))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected post does not exist.');
                    else
                    {
                        $oldFlag = Flag::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                        if(!is_null($oldFlag))
                            $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have already flagged this post.');
                        else
                        {
                            $flag = new Flag('add_api');
                            $flag->userId = $userId;
                            $flag->postId = $postId;
//                             $flag->postUserId = $post->userId;

                            //save record
                            $flag->save();
                            if ($flag->hasErrors()) 
                            {
                                throw new Exception(print_r($flag->getErrors(), true), WS_ERR_UNKNOWN);
                            }
                            
                            //save event
//                             Event::saveEvent(Event::POST_FLAGGED, $userId, $flag->id, $flag->createDate, $post->userId);

                            //send notifications to followers of the post user
//                            $sql = Follower::getQueryForFollower($post->userId);
//                            $sql .= " AND (s.deviceToken is not null OR s.deviceToken!='')";
//                            $followers = Yii::app()->db->createCommand($sql)->queryAll(true);
//                            $message = $user->userName . ' has flagged a post.';
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
                                'apiMessage' => 'Your flag marked successfully.',
                                'status' => 'OK',
                                'flagId' => $flag->id
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
     * Add flag to a comment
     */
    
    public function actionComment()
    {
    	$apiName = 'flag/comment';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else if(!isset($_JSON['commentId']) || empty($_JSON['commentId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter comment id.');
    		else
    		{
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
    				$commentId = filter_var($_JSON['commentId'], FILTER_SANITIZE_NUMBER_INT);
    				$Comment = Comment::model()->findByPk($commentId);
    				if (is_null($Comment))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected Comment does not exist.');
    				else
    				{
    					$oldFlag = Flag::model()->findByAttributes(array('userId'=>$userId, 'commentId'=>$commentId));
    					if(!is_null($oldFlag))
    						$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have already flagged this Comment.');
    					else
    					{
    						$flag = new Flag('add_api');
    						$flag->userId = $userId;
    						$flag->commentId = $commentId;
    
    						//save record
    						$flag->save();
    						if ($flag->hasErrors())
    						{
    							throw new Exception(print_r($flag->getErrors(), true), WS_ERR_UNKNOWN);
    						}
    
    						$result = array(
    								'api' => $apiName,
    								'apiMessage' => 'Your flag marked successfully.',
    								'status' => 'OK',
    								'flagId' => $flag->id
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
     *  flag a user
     */
    
    public function actionUser()
    {
    	$apiName = 'flag/user';
    	$sessionId = null;
    
    	$_JSON = $this->getJsonInput();
    
    	try
    	{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
    		else if(!isset($_JSON['userId']) || empty($_JSON['userId']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user id.');
    		else
    		{
    			$userId = $this->isAuthentic($_JSON['sessionId']);
    			$user = User::model()->findByPk($userId);
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else
    			{
//     				$commentId = filter_var($_JSON['commentId'], FILTER_SANITIZE_NUMBER_INT);
//     				$Comment = Comment::model()->findByPk($commentId);

    				$ReportedUserId = filter_var($_JSON['userId'], FILTER_SANITIZE_NUMBER_INT);
    				$ReportedUser = User::model()->findByPk($ReportedUserId);
    				
    				
    				if (is_null($ReportedUser))
    					$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected User does not exist.');
    				else
    				{
    					$oldFlag = Flag::model()->findByAttributes(array('userId'=>$userId, 'postUserId'=>$ReportedUserId));
    					if(!is_null($oldFlag))
    						$result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have already flagged.');
    					else
    					{
    						$flag = new Flag('add_api');
    						$flag->userId = $userId;
    						$flag->postUserId = $ReportedUserId;
    
    						//save record
    						$flag->save();
    						if ($flag->hasErrors())
    						{
    							throw new Exception(print_r($flag->getErrors(), true), WS_ERR_UNKNOWN);
    						}
    
    						$result = array(
    								'api' => $apiName,
    								'apiMessage' => 'Your flag marked successfully.',
    								'status' => 'OK',
    								'flagId' => $flag->id
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
     * Remove flag from a post
     */
    public function actionDelete()
    {
        $apiName = 'flag/delete';
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
                
                $postId = filter_var($_JSON['postId'], FILTER_SANITIZE_NUMBER_INT);
                $post = Post::model()->findByPk($postId);
                    
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else if (is_null($post))
                    $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected post does not exist.');
                else
                {
                    $flag = Flag::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                    if(is_null($flag))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have not flagged this post.');
                    else
                    {
                        $flag->delete();
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Your flag removed successfully.',
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
    
    /**
     * List flags of a post
     */
    public function actionListByPost()
    {
        $apiName = 'flag/listByPost';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter post id whose flags are to be listed.');
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
                        
                        $sql = Flag::getQuery($imagePath, $thumbPath, $postId, $userId);
                        $flags = Yii::app()->db->createCommand($sql)->queryAll(true);

                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Flags fetched successfully.',
                            'status' => 'OK',
                            'flags' => $flags
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