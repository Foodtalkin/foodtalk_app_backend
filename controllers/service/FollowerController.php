<?php
class FollowerController extends ServiceBaseController
{
    /**
     * Follow a user
     */
    public function actionFollow()
    {
        $apiName = 'follower/follow';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['followedUserId']) || empty($_JSON['followedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select a friend to follow.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                $followedUserId = filter_var($_JSON['followedUserId'], FILTER_SANITIZE_NUMBER_INT);
                $followedUser = User::model()->findByPk($followedUserId);
                
                $follower = Follower::model()->findByAttributes(array('followerUserId'=>$userId, 'followedUserId'=>$followedUserId));
                
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else if (is_null($followedUser))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected user does not exist.');
                else if (!is_null($follower))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected user is already being followed.');
                else
                {
                    $follower = new Follower('follow_api');
                    $follower->followerUserId = $userId;
                    $follower->followedUserId = $followedUserId;

                    //save record
                    $follower->save();
                    if ($follower->hasErrors())
                    {
                        throw new Exception(print_r($follower->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    
                    //save event
                    Event::saveEvent(Event::USER_FOLLOWED, $userId, $userId, $follower->createDate, $followedUserId);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User followed successfully.',
                        'status' => 'OK',
                        'followerId' => $follower->id
                    );
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Follow a user
     */
    public function actionFollowBulk()
    {
        $apiName = 'follower/followBulk';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['followedUserIds']) || empty($_JSON['followedUserIds']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select a friend to follow.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $followedUserIdCsv = filter_var($_JSON['followedUserIds'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    $followedUserIds = explode(',', $followedUserIdCsv);
                    foreach ($followedUserIds as $followedUserId)
                    {
                        if($userId != $followedUserId)
                        {
                            $follower = Follower::model()->findByAttributes(array('followerUserId'=>$userId, 'followedUserId'=>$followedUserId));
                            if(is_null($follower))
                            {
                                $follower = new Follower('follow_api');
                                $follower->followerUserId = $userId;
                                $follower->followedUserId = $followedUserId;

                                //save record
                                $follower->save();

                                //save event
                                Event::saveEvent(Event::USER_FOLLOWED, $userId, $follower->id, $follower->createDate, $followedUserId);
                            }
                        }
                    }
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User(s) followed successfully.',
                        'status' => 'OK'
                    );
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Unfollow a user
     */
    public function actionUnfollow()
    {
        $apiName = 'follower/unfollow';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['followedUserId']) || empty($_JSON['followedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select a friend to follow.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                $followedUserId = filter_var($_JSON['followedUserId'], FILTER_SANITIZE_NUMBER_INT);
                $followedUser = User::model()->findByPk($followedUserId);
                
                $follower = Follower::model()->findByAttributes(array('followerUserId'=>$userId, 'followedUserId'=>$followedUserId));
                
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else if (is_null($followedUser))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected user does not exist.');
                else if (is_null($follower))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected user is not being followed.');
                else
                {
                    if ($follower->delete())
                    {
                        $result = $this->error($apiName, WS_ERR_UNKNOWN, 'Unable to unfollow the user.');
                    }

                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User unfollowed successfully.',
                        'status' => 'OK'
                    );
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * List all followers
     */
    public function actionListFollowers()
    {
        $apiName = 'follower/listFollowers';
        $sessionId = null;
        $selectedUser = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select a user.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $selectedUserId = filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    $selectedUser = User::model()->findByAttributes(array('id'=>$selectedUserId, 'isActivated'=>1, 'isDisabled'=>0));
                    if (is_null($selectedUser))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exits.');
                    else
                    {
                        $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                        $thumbPath = $imagePath . 'thumb/';
                        $sql = Follower::getQueryForFollower($selectedUserId, $userId);
                        $followers = Yii::app()->db->createCommand($sql)->queryAll(true);

                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Followers fetched successfully.',
                            'status' => 'OK',
                            'followers' => $followers
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
     * List all followed users
     */
    public function actionListFollowed()
    {
        $apiName = 'follower/listFollowed';
        $sessionId = null;
        $selectedUser = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['selectedUserId']) || empty($_JSON['selectedUserId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please select a user.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $selectedUserId = filter_var($_JSON['selectedUserId'], FILTER_SANITIZE_NUMBER_INT);
                    $selectedUser = User::model()->findByAttributes(array('id'=>$selectedUserId, 'isActivated'=>1, 'isDisabled'=>0));
                    if (is_null($selectedUser))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected user does not exits.');
                    else
                    {
                        $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                        $thumbPath = $imagePath . 'thumb/';
                        
                       	$name='';
        				if(isset($_JSON['name']) && strlen($_JSON['name']) > 0)
                        	$name = trim($_JSON['name']);      						
                        
                        $sql = Follower::getQueryForFollowed($selectedUserId, $name);
                        $followedUsers = Yii::app()->db->createCommand($sql)->queryAll(true);

                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Followed users fetched successfully.',
                            'status' => 'OK',
                            'followedUsers' => $followedUsers
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