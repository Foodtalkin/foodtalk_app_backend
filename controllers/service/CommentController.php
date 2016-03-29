<?php
class CommentController extends ServiceBaseController
{
    /**
     * Add comment to a post
     */
    public function actionAdd()
    {
        $apiName = 'comment/add';
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
            else if(!isset($_JSON['comment']) || empty($_JSON['comment']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter your comment.');
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
                        $comment = new Comment('add_api');
                        $comment->userId = $userId;
                        $comment->postId = $postId;
                        $comment->postUserId = $post->userId;
                        $comment->comment = filter_var(base64_decode($_JSON['comment']), FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
//                         $comment->comment = $_JSON['comment'];
                        //$comment->comment = filter_var($_JSON['comment'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        //save record
                        $comment->save();
                        
                        
                        if(isset($_JSON['userMentioned']) && count($_JSON['userMentioned']) > 0){
                        	foreach ($_JSON['userMentioned']as $userM){	
	                        	$userMentioned = new UserMentioned();
	                        	$userMentioned->userId = filter_var($userM['userId'], FILTER_SANITIZE_NUMBER_INT);
// 	                        	$userMentioned->userName = filter_var(base64_decode($userM['userName']), FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
	                        	$userMentioned->userName = $userM['userName'];
	                        	$userMentioned->postId = $postId;
	                        	$userMentioned->commentId = $comment->id;
	                        	$userMentioned->save();
	                        	Event::saveEvent(Event::USER_MENTIONED_COMMENT, $userId, $comment->postId, $comment->createDate, $userMentioned->userId);
                        	}
                        }
                        
                        
                        if ($comment->hasErrors()) 
                        {
                            throw new Exception(print_r($comment->getErrors(), true), WS_ERR_UNKNOWN);
                        }
                        
                        //save event
                        Event::saveEvent(Event::COMMENT_CREATED, $userId, $comment->postId, $comment->createDate, $post->userId);
                        
                        //send notifications to followers of the post user
//                        $sql = Follower::getQueryForFollower($post->userId);
//                        $sql .= " AND (s.deviceToken is not null OR s.deviceToken!='')";
//                        $followers = Yii::app()->db->createCommand($sql)->queryAll(true);
//                        $message = $user->userName . ' has commented on a post.';
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
                        
                        $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
                        $thumbPath = Yii::app()->getBaseUrl(true) . '/images/user/thumb/';
                        
                        $sql = Comment::getQuery($imagePath, $thumbPath, $postId);
                        $sql .= ' AND c.id=' . $comment->id;
                        $newComment = Yii::app()->db->createCommand($sql)->queryRow(true);
                        $newComment['timeElapsed'] = getTimeElapsed(date_create($newComment['createDate']), date_create($newComment['currentDate']));
                        $newComment['userMentioned'] = UserMentioned::getUserByComment($newComment['id']);
                        
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Comment saved successfully.',
                            'status' => 'OK',
                            'commentId' => $comment->id,
                            'comment' => $newComment
                        );
                    }
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($e->getCode(), Yii::t('app', $e->getMessage()),'TSASTT');
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Remove comment from a post
     */
    public function actionDelete()
    {
        $apiName = 'comment/delete';
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
                    $comment = Comment::model()->findByAttributes(array('id'=>$commentId),  '(userId=:uid or postUserId=:uid)', array(':uid'=>$userId));
                    if (is_null($comment))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected comment does not exist.');
                    else
                    {
                        //delete record
                        $comment->delete();
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Comment deleted successfully.',
                            'status' => 'OK'
                        );
                    }
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
     * List comments of a post
     */
    public function actionList()
    {
        $apiName = 'comment/list';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['postId']) || empty($_JSON['postId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter post id whose comments are to be listed.');
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
//                        $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
//                        $thumbPath = Yii::app()->getBaseUrl(true) . '/images/user/thumb/';
//                        
//                        $sql = Comment::getQuery($imagePath, $thumbPath, $postId);
//                        $comments = Yii::app()->db->createCommand($sql)->queryAll(true);
                        
                        $comments = Comment::getCommentsByPostId($postId);
                        foreach ($comments as &$comment)
                        {
                            $comment['timeElapsed'] = getTimeElapsed(date_create($comment['createDate']), date_create($comment['currentDate']));
                            $comment['userMentioned'] = UserMentioned::getUserByComment($comment['id']); 
                        }
                        
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Comments fetched successfully.',
                            'status' => 'OK',
                            'comments' => $comments
                        );
                    }
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
}