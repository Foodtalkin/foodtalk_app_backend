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
                	
                	
                	$sql = "select platform, count(1) as cnt from (SELECT DISTINCT u.id, IF(SUBSTRING_INDEX(platform, 'FoodTalk/com.foodtalkindia.FoodTalk', 1) = '', 'ios', IF(platform = 'FoodTalk webapp 1.0', 'web', 'android')) as platform FROM user u INNER JOIN `access_logs` l on l.user_id = u.id WHERE u.userName is NOT null AND l.method ='POST' and l.platform is not null) tmp 
                			GROUP by platform"; 
                	$totalUsers = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = 'SELECT count(1) cnt FROM `post`, user WHERE post.userId = user.id AND post.isDisabled = 0';
                	$totalpost = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = 'SELECT count(1) cnt FROM `post`, user WHERE post.userId = user.id AND post.isDisabled = 0  AND user.role = "manager"';
                	$internalPost = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = "SELECT DATE_FORMAT(u.createDate,'%d-%m-%Y') as onbord, COUNT(1) as cnt FROM `user` u WHERE createDate >= DATE(NOW()) - INTERVAL 7 DAY and u.userName is not null GROUP BY onbord ";                	 
                	$onbording = Yii::app()->db->createCommand($sql)->queryAll(true);

                	
                	$sql = "select onbord, count(1) as cnt, platform from  (SELECT DISTINCT DATE_FORMAT(u.createDate,'%d-%m-%Y') as onbord, u.id, IF(SUBSTRING_INDEX(platform, 'FoodTalk/com.foodtalkindia.FoodTalk', 1) = '', 'ios', IF(platform = 'FoodTalk webapp 1.0', 'web', 'android')) as  platform FROM user u  INNER JOIN `access_logs`  l on l.user_id = u.id 
WHERE u.createDate >= DATE(NOW()) - INTERVAL 7 DAY and u.userName is NOT null) tmp GROUP by onbord, platform ";
                	$onbordingPlatforms = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	$sql = "SELECT  acitve_on, COUNT(1) as cnt, platform FROM (SELECT DISTINCT DATE_FORMAT(l.timestamp,'%d-%m-%Y') as acitve_on, u.id, IF(SUBSTRING_INDEX(platform, 'FoodTalk/com.foodtalkindia.FoodTalk', 1) = '', 'ios', IF(platform = 'FoodTalk webapp 1.0', 'web', 'android')) as  platform FROM user u  INNER JOIN `access_logs`  l on l.user_id = u.id 
WHERE l.timestamp >= DATE(NOW()) - INTERVAL 7 DAY and u.userName is NOT null and u.id >0) tmp GROUP BY acitve_on, platform ";
             		$weeklyactiveuser = Yii::app()->db->createCommand($sql)->queryAll(true);
                	
                	
             		$sql= "SELECT DATE_FORMAT(post.createDate,'%d-%m-%Y') as createon , count(1) cnt FROM `post`, user WHERE post.userId = user.id AND post.isDisabled = 0 and post.createDate >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY createon";
             		$weeklyPosts = Yii::app()->db->createCommand($sql)->queryAll(true);

             		
             		$sql= "SELECT DATE_FORMAT(like.createDate,'%d-%m-%Y') as createon , count(1) cnt FROM `like`, user WHERE like.userId = user.id AND like.isDisabled = 0 and like.createDate >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY createon";
             		$weeklylikes = Yii::app()->db->createCommand($sql)->queryAll(true);
             		 
             		$sql= "SELECT DATE_FORMAT(comment.createDate,'%d-%m-%Y') as createon , count(1) cnt FROM `comment`, user WHERE comment.userId = user.id AND comment.isDisabled = 0 and comment.createDate >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY createon";
             		$weeklycomments = Yii::app()->db->createCommand($sql)->queryAll(true);
             		 
             		$sql = "SELECT DATE_FORMAT(bookmark.createDate,'%d-%m-%Y') as createon , count(1) cnt FROM `bookmark`, user WHERE bookmark.userId = user.id AND bookmark.isDisabled = 0 and bookmark.createDate >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY createon";
             		$weeklyBookmark = Yii::app()->db->createCommand($sql)->queryAll(true);
             		
             		$sql = "SELECT DATE_FORMAT(userMentioned.createDate,'%d-%m-%Y') as createon , count(1) cnt FROM `userMentioned`, user WHERE userMentioned.userId = user.id AND  userMentioned.createDate >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY createon";
             		$weeklyuserMentioned = Yii::app()->db->createCommand($sql)->queryAll(true);
             		
                	
//                 	$onbordingJson = '[{"onbord":"22-07-2016","cnt":"15","platform":"android"}, {"onbord":"22-07-2016","cnt":"17","platform":"ios"}, {"onbord":"23-07-2016","cnt":"14","platform":"android"}, {"onbord":"23-07-2016","cnt":"15","platform":"ios"}, {"onbord":"23-07-2016","cnt":"2","platform":"web"}, {"onbord":"24-07-2016","cnt":"15","platform":"android"}, {"onbord":"24-07-2016","cnt":"12","platform":"ios"}, {"onbord":"25-07-2016","cnt":"14","platform":"android"}, {"onbord":"25-07-2016","cnt":"15","platform":"ios"}, {"onbord":"26-07-2016","cnt":"34","platform":"android"}, {"onbord":"26-07-2016","cnt":"27","platform":"ios"}, {"onbord":"27-07-2016","cnt":"14","platform":"android"}, {"onbord":"27-07-2016","cnt":"16","platform":"ios"}, {"onbord":"28-07-2016","cnt":"8","platform":"android"}, {"onbord":"28-07-2016","cnt":"11","platform":"ios"}, {"onbord":"28-07-2016","cnt":"1","platform":"web"}, {"onbord":"29-07-2016","cnt":"4","platform":"android"}, {"onbord":"29-07-2016","cnt":"4","platform":"ios"}, {"onbord":"29-07-2016","cnt":"1","platform":"web"}]';
                	
//                 	$onbordingJson = '[{"onbord":"22-07-2016","cnt":"32"}, {"onbord":"23-07-2016","cnt":"30"}, {"onbord":"24-07-2016","cnt":"27"}, {"onbord":"25-07-2016","cnt":"29"}, {"onbord":"26-07-2016","cnt":"61"}, {"onbord":"27-07-2016","cnt":"30"}, {"onbord":"28-07-2016","cnt":"20"}, {"onbord":"29-07-2016","cnt":"8"}]';
                	
//                 	$onbording = json_decode($onbordingJson, true);
                			
                			
                	
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