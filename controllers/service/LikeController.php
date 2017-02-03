<?php
class LikeController extends ServiceBaseController
{
    /**
     * Add like to a post
     */
    public function actionAdd()
    {
        $apiName = 'like/add';
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
                        	$like = Like::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                        	
                        	if(is_null($like)){
                        		
                        		$is_new = true;                        		
	                            $like = new Like('add_api');
	                            $like->userId = $userId;
	                            $like->postId = $postId;
	                            $like->postUserId = $post->userId;
                        	}else{
                        		
                        		$is_new = false;
                        		$like->isDisabled = 0;
                        		
                        	}
                            //save record
                            $like->save();
                            if ($like->hasErrors()) 
                            {
                                throw new Exception(print_r($like->getErrors(), true), WS_ERR_UNKNOWN);
                            }
                            
                            if($is_new){
                            	if($post->type == 'question')
                            		$eventtype = 13;
                            	else
                            		$eventtype = Event::POST_LIKED;
                            	
	                            Event::saveEvent(Event::POST_LIKED, $userId, $like->postId, $like->createDate, $post->userId);
                            }

                            $result = array(
                                'api' => $apiName,
                                'apiMessage' => 'Your like marked successfully.',
                                'status' => 'OK',
                                'likeId' => $like->id,
                                'postId' => $postId
                            );
//                         }
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
     * Remove like from a post
     */
    public function actionDelete()
    {
        $apiName = 'like/delete';
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
                    $like = Like::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                    if(is_null($like))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have not liked this post.');
                    else
                    {
                    	
                        $like->isDisabled = 1;
                        $like->save();
//                         $like->delete();
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Your like removed successfully.',
                            'status' => 'OK',
                            'postId' => $postId
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
     * List likes of a post
     */
    public function actionListByPost()
    {
        $apiName = 'like/listByPost';
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
                        
                        $sql = Like::getQuery($imagePath, $thumbPath, $postId, $userId);
                        $likes = Yii::app()->db->createCommand($sql)->queryAll(true);

                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Likes fetched successfully.',
                            'status' => 'OK',
                            'likes' => $likes
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