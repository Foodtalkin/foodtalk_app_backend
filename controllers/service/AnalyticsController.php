<?php
class AnalyticsController extends ServiceBaseController
{
    /**
     * List tags
     */
    public function actionSummary()
    {
        $apiName = 'analytics/summary';
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
                	
                	
                	$sql = "select platform, count(1) as cnt from (SELECT DISTINCT u.id, IF(SUBSTRING_INDEX(platform, 'FoodTalk/com.foodtalkindia.FoodTalk', 1) = '', 'ios', IF(SUBSTRING_INDEX(platform, 'iOS', 1) = '','ios', IF(platform = 'FoodTalk webapp 1.0', 'web', 'android'))) as platform FROM user u INNER JOIN `access_logs` l on l.user_id = u.id WHERE u.userName is NOT null AND l.method ='POST' and l.platform is not null) tmp GROUP by platform"; 
                	$totalUsers = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = 'SELECT count(1) cnt FROM `post`, user WHERE post.userId = user.id AND post.isDisabled = 0';
                	$totalpost = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = 'SELECT count(1) cnt FROM `post`, user WHERE post.userId = user.id AND post.isDisabled = 0  AND user.role = "manager"';
                	$internalPost = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = "SELECT DATE_FORMAT(u.createDate,'%Y-%m-%d') as onbord, COUNT(1) as cnt FROM `user` u WHERE createDate >= DATE(NOW()) - INTERVAL 6 DAY and u.userName is not null GROUP BY onbord ORDER BY onbord ASC";                	 
                	$onbording = Yii::app()->db->createCommand($sql)->queryAll(true);

                	
                	$sql = "SELECT theday as onbord, name  as platform, IFNULL(cnt, 0 ) as cnt FROM ( SELECT DISTINCT DATE_FORMAT(timestamp,'%Y-%m-%d') as theday, name
FROM access_logs , device
WHERE timestamp >= DATE(NOW()) - INTERVAL 6 DAY ) as calander 
LEFT JOIN
( SELECT onbord, count(1) as cnt, platform from  (SELECT DISTINCT DATE_FORMAT(u.createDate,'%Y-%m-%d') as onbord, u.id, IF(SUBSTRING_INDEX(platform, 'FoodTalk/com.foodtalkindia.FoodTalk', 1) = '', 'ios', IF(SUBSTRING_INDEX(platform, 'iOS', 1) = '','ios', IF(platform = 'FoodTalk webapp 1.0', 'web', 'android'))) as  platform FROM user u  INNER JOIN `access_logs`  l on l.user_id = u.id 
WHERE u.createDate >= DATE(NOW()) - INTERVAL 6 DAY and u.userName is NOT null) tmp GROUP by onbord, platform ) as tt on tt.onbord = calander.theday and BINARY calander.name = BINARY tt.platform 
ORDER by onbord ASC";
                	$onbordingPlatforms = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = "SELECT theday as acitve_on, name  as platform, IFNULL(cnt, 0 ) as cnt FROM ( SELECT DISTINCT DATE_FORMAT(timestamp,'%Y-%m-%d') as theday, name
FROM access_logs , device
WHERE timestamp >= DATE(NOW()) - INTERVAL 6 DAY ) as calander 
LEFT JOIN
( SELECT  acitve_on, COUNT(1) as cnt, platform FROM (SELECT DISTINCT DATE_FORMAT(l.timestamp,'%Y-%m-%d') as acitve_on, u.id, IF(SUBSTRING_INDEX(platform, 'FoodTalk/com.foodtalkindia.FoodTalk', 1) = '', 'ios', IF(SUBSTRING_INDEX(platform, 'iOS', 1) = '','ios', IF(platform = 'FoodTalk webapp 1.0', 'web', 'android'))) as  platform FROM user u  INNER JOIN `access_logs`  l on l.user_id = u.id 
WHERE l.timestamp >= DATE(NOW()) - INTERVAL 6 DAY and u.userName is NOT null and u.id >0) tmp GROUP BY acitve_on, platform  ) as tt on tt.acitve_on = calander.theday and BINARY calander.name = BINARY tt.platform 
ORDER by acitve_on ASC";
             		$weeklyactiveuser = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	
             		$sql= "SELECT DATE_FORMAT(post.createDate,'%Y-%m-%d') as createon , count(1) cnt FROM `post`, user WHERE post.userId = user.id AND post.isDisabled = 0 and post.createDate >= DATE(NOW()) - INTERVAL 6 DAY GROUP BY createon ORDER BY `createon` ASC";
             		$weeklyPosts = Yii::app()->db->createCommand($sql)->queryAll(true);

             		
             		$sql= "SELECT DATE_FORMAT(like.createDate,'%Y-%m-%d') as createon , count(1) cnt FROM `like`, user WHERE like.userId = user.id AND like.isDisabled = 0 and like.createDate >= DATE(NOW()) - INTERVAL 6 DAY GROUP BY createon ORDER BY `createon` ASC";
             		$weeklylikes = Yii::app()->db->createCommand($sql)->queryAll(true);
             		 
             		$sql= "SELECT DATE_FORMAT(comment.createDate,'%Y-%m-%d') as createon , count(1) cnt FROM `comment`, user WHERE comment.userId = user.id AND comment.isDisabled = 0 and comment.createDate >= DATE(NOW()) - INTERVAL 6 DAY GROUP BY createon ORDER BY `createon` ASC";
             		$weeklycomments = Yii::app()->db->createCommand($sql)->queryAll(true);
             		 
             		$sql = "SELECT DATE_FORMAT(bookmark.createDate,'%Y-%m-%d') as createon , count(1) cnt FROM `bookmark`, user WHERE bookmark.userId = user.id AND bookmark.isDisabled = 0 and bookmark.createDate >= DATE(NOW()) - INTERVAL 6 DAY GROUP BY createon ORDER BY `createon` ASC";
             		$weeklyBookmark = Yii::app()->db->createCommand($sql)->queryAll(true);
             		
             		$sql = "SELECT DATE_FORMAT(userMentioned.createDate,'%Y-%m-%d') as createon , count(1) cnt FROM `userMentioned`, user WHERE userMentioned.userId = user.id AND  userMentioned.createDate >= DATE(NOW()) - INTERVAL 6 DAY GROUP BY createon ORDER BY `createon` ASC";
             		$weeklyuserMentioned = Yii::app()->db->createCommand($sql)->queryAll(true);
             		
                			
                	
                	$result = array(
                			'overall'=> array(
                					'user'=>$totalUsers,
                					'posts'=>$totalpost,
                					'internalposts'=>$internalPost
                			),
                			'weakly'=>array(
                					'onbordingUsers' =>$onbording,
                					'onbordingUserWithPlatform'=>$onbordingPlatforms,
                					'activeUser'=> $weeklyactiveuser,
                					'posts'=> $weeklyPosts,
                					'likes'=> $weeklylikes,
                					'comments' => $weeklycomments,
                					'bookmarks'=>$weeklyBookmark,
                					'userMentioned' => $weeklyuserMentioned
                					
                			)
                			
                	);

                	
                	
//                     $search = '';
//                     if(isset($_JSON['search']) && $_JSON['search'])
//                         $search = filter_var($_JSON['search'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    
//                     $region = '';
//                     if(isset($_JSON['region']) && $_JSON['region'])
//                     	$region = filter_var($_JSON['region'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    

//                     $result = Dish::listByName($search, $region);




                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'success',
                        'status' => 'OK',
                        'result' => $result
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
    
    public function actionList()
    {
    	$apiName = 'dish/list';
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
    				
    				$id = 0;
    				if(isset($_JSON['id']) && $_JSON['id'])
    					$id = filter_var($_JSON['id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
    
    				$result = Dish::listAll(array('id'=>$id));
    				$result = array(
    						'api' => $apiName,
    						'apiMessage' => 'Tags fetched successfully.',
    						'status' => 'OK',
    						'result' => $result
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