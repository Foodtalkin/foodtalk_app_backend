<?php
class NotificationController extends ServiceBaseController
{
    /**
     * List notifications by receiver
     */
    public function actionList()
    {
        $apiName = 'notification/list';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['notificationGroup']) || empty($_JSON['notificationGroup']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter notification group.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $notificationGroup = filter_var($_JSON['notificationGroup'], FILTER_SANITIZE_NUMBER_INT);
                    $notifications = Notification::getNotificationsByReceiverId($userId, $notificationGroup);
                    
                    foreach($notifications as &$notification)
                    {
                        $notification['timeElapsed'] = getTimeElapsed(date_create($notification['eventDate']), date_create());
                    }
                    
                    //reset badge counter
                    Session::model()->updateAll(array('deviceBadge'=>0),'userId='.$userId);
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Notifications fetched successfully.',
                        'status' => 'OK',
                        'notifications' => $notifications
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
     * Remove notification from a post
     */
    public function actionDelete()
    {
        $apiName = 'notification/delete';
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
                    $notification = Notification::model()->findByAttributes(array('userId'=>$userId, 'postId'=>$postId));
                    if(is_null($notification))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have not notificationd this post.');
                    else
                    {
                        $notification->delete();
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Your notification removed successfully.',
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
}