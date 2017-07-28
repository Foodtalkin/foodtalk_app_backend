<?php
class AuthController extends ServiceBaseController
{
    /**
     * Login user (create sessionId)
     * @param $userId
     * @param $role
     * @param $deviceToken
     * @throws Exception
     * @return mixed Session ID
     */
    private function login($userId, $role, $deviceToken = '')
    {
        if (null === ($session = Session::model()->findByAttributes(array('userId' => $userId)))) 
        {
            $session = new Session();
            $session->userId = $userId;
            $session->role = $role;
            $session->deviceToken = $deviceToken;
        }
        $i = 0;
        do
        {
        	
        	if($session->isNewRecord){
	            $sessionId = sha1(microtime());
	            $session->sessionId = $sessionId;
	            $session->refreshToken = sha1(microtime());
        	}
            // Safety guard
            if (++$i > 10) 
            {
                throw new Exception('Unknown error 1.', WS_ERR_UNKNOWN);
            }
        } while (!$session->save() && $session->hasErrors('sessionId'));
        if ($session->hasErrors()) 
        {
            throw new Exception(print_r($session->getErrors(), true), WS_ERR_UNKNOWN);
        }

        return $session;
    }
    
    public function actionRefresh(){
    	$apiName = 'auth/refresh';
    	$_JSON = $this->getJsonInput();

    	try{
    		if(!isset($_JSON) || empty($_JSON))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
    		else if(!isset($_JSON['refreshToken']) || empty($_JSON['refreshToken']))
    			$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, ' refreshToken is required ');
    		else {
    			$newSession = Session::refreshSession($_JSON['refreshToken']);

				if(empty($newSession))
					throw new Exception(' Invalid/Expired refreshToken ', WS_ERR_REQUEST_NOT_ACCEPTED);

				$user = User::model()->findByPk($newSession->userId);
    			if (is_null($user))
    				$result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
    			else {
    				$result['api'] = $apiName;
    				$result['apiMessage'] = 'Success';
    				$result['status'] = 'OK';
    				$result['sessionId'] = $newSession->sessionId;
    				$result['refreshToken'] = $newSession->refreshToken;
    			}
    		}
    	}
    	catch (Exception $e)
    	{
    		$result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
    	}
    	$this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    
    public function actionAppVersionControl()
    {
    	$apiName = 'auth/appversion';
    	$msg = 'App Version details fetched successfully.';
    	 
    	$version = Yii::app()->db->createCommand('select * from appVersion')->queryAll(true);
    	self::actionCloudAccess($apiName, $msg, $version);
    }
    
    public function actionAppVersion()
    {
    	$apiName = 'auth/appversion';
    	$msg = 'App Version details fetched successfully.';
    	$version = array(
//     			'current'=>'2.4',
    			'allowed'=>'2.0',
    			'text'=> 'please update, your app is obsolete!'
    	);
    	self::actionCloudAccess($apiName, $msg, $version);
    }
    
    public function actionCloudAccess($apiName = 'auth/cloudaccess', $msg = 'Access details fetched successfully.', $version = array())
    {
//     	$apiName = 'auth/cloudaccess';
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
    				$result['api'] = $apiName;
    				$result['apiMessage'] = $msg;
    				$result['status'] = 'OK';    				
    				if(!empty($version)){
    					$result['app_version'] = $version;
    				}    				
    				$result['cloud_name'] = 'digital-food-talk-pvt-ltd';
//     				$result['api_key'] = '849964931992422';
    				$result['api_key'] = '849964931992422false';
//     				$result['api_secret'] = '_xG26XxqmqCVcpl0l9-5TJs77Qc';
    				$result['api_secret'] = 'false';
    				$result['environment_variable'] = 'CLOUDINARY_URL=cloudinary://849964931992422:_xG26XxqmqCVcpl0l9-5TJs77Qc@digital-food-talk-pvt-ltd';
    				$result['base_delivery_url'] = 'http://res.cloudinary.com/digital-food-talk-pvt-ltd';
    				$result['secure_delivery_url'] = 'https://res.cloudinary.com/digital-food-talk-pvt-ltd';
    				$result['api_base_url'] = 'https://api.cloudinary.com/v1_1/digital-food-talk-pvt-ltd';
    				
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
     * Signin a user with facebook, twitter or google+
     * if record exists, update it and return userId and sessionId
     * if record does not exist create a new user and return userId and sessionId
     */
    public function actionSignin()
    {
        $apiName = 'auth/signin';
        $signInType=null;   /*F:Facebook, T:Twitter, G:Google*/
        $facebookId=null;
        $twitterId=null;
        $googleId=null;
        $userName=null;
        $email=null;
        $latitude=0;
        $longitude=0;
        $image=null;
        $deviceToken=null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['signInType']) || empty($_JSON['signInType']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter the social media.');
//            else if(!isset($_JSON['userName']) || empty($_JSON['userName']))
//                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user name.');
// creating device token and full name mindatory 
            else if(!isset($_JSON['deviceToken']) || empty($_JSON['deviceToken']))
            	$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Invalid or no device token');
            else if(!isset($_JSON['fullName']) || empty($_JSON['fullName']))
            	$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Full name is required');
            
//             else if(!isset($_JSON['email']) || empty($_JSON['email']))
//                 $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter email.');
//             else if(!filter_var($_JSON['email'], FILTER_VALIDATE_EMAIL))
//                 $result = $this->error($apiName, WS_ERR_WONG_FORMAT, 'Please enter a valid email.');
//            else if(!is_null(User::model()->findByAttributes(array('email' => $_JSON['email']))))
//                $result = $this->error($apiName, 99, 'This email is already registered.');
            else
            {
                $signInType = filter_var($_JSON['signInType'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                if(!($signInType=='F' || $signInType=='T' || $signInType=='G'))
                    $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Please enter a valid social media.');
                else if($signInType=='F' && (!isset($_JSON['facebookId']) || empty($_JSON['facebookId'])))
                    $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter facebook id.');
                else if($signInType=='T' && (!isset($_JSON['twitterId']) || empty($_JSON['twitterId'])))
                    $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter twitter id.');
                else if($signInType=='G' && (!isset($_JSON['googleId']) || empty($_JSON['googleId'])))
                    $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter google id.');
                else
                {
                    $user = null;
                    $isNewUser = 0; //to differentiate between signup and signin
                    
                    if($signInType=='F')
                    {
                        $facebookId = filter_var($_JSON['facebookId'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        if(!$facebookId)
                            $result = $this->error($apiName, WS_ERR_WONG_FORMAT, 'Please enter a valid facebook id.');
                        else
                        {
                        	
                        	if(isset($_JSON['email']))
                        		$email = filter_var($_JSON['email'], FILTER_SANITIZE_EMAIL);
                        	
                        	$user = User::model()->findByAttributes(array('facebookId' => $facebookId));
                        	    
                        	if($email && (is_null($user) || empty($user))){
                        		$user = User::model()->findByAttributes(array('email' => $email));
                        	}
//                         	else
                        	
                        	
                            if(is_null($user) || empty($user))
                            {
                            	if(!isset($_JSON['userName']) || empty($_JSON['userName']))
                            		$result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter user name.');
                            	 
                                $user = new User('register_api');
                                $user->facebookId = $facebookId;
                                $isNewUser = 1;
                                
                            }else 
                            	$user->facebookId = $facebookId;
                        }
                    }
                    else if($signInType=='T')
                    {
                        $twitterId = filter_var($_JSON['twitterId'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        if(!$twitterId)
                            $result = $this->error($apiName, WS_ERR_WONG_FORMAT, 'Please enter a valid twitter id.');
                        else
                        {
                            $user = User::model()->findByAttributes(array('twitterId' => $twitterId));
                            if(is_null($user) || empty($user))
                            {
                                $user = new User('register_api');
                                $user->twitterId = $twitterId;
                                
                                $isNewUser = 1;
                            }
                        }
                    }
                    else        // if($signInType=='G')
                    {
                        $googleId = filter_var($_JSON['googleId'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                        if(!$googleId)
                            $result = $this->error($apiName, WS_ERR_WONG_FORMAT, 'Please enter a valid google id.');
                        else
                        {
                            $user = User::model()->findByAttributes(array('googleId' => $googleId));
                            if(is_null($user) || empty($user))
                            {
                                $user = new User('register_api');
                                $user->googleId = $googleId;
                                
                                $isNewUser = 1;
                            }
                        }
                    }
                    if(isset($_JSON['email']))
	                    $user->email = filter_var($_JSON['email'], FILTER_SANITIZE_EMAIL);
                    
                    if(isset($_JSON['userName']))
                        $user->userName = filter_var($_JSON['userName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['birthday']))
                    	$user->birthday = filter_var($_JSON['birthday'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['gender']))
                    	$user->gender = filter_var($_JSON['gender'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['fullName']))
                        $user->fullName = filter_var($_JSON['fullName'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(isset($_JSON['latitude']))
                        $user->latitude = filter_var($_JSON['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if(isset($_JSON['longitude']))
                        $user->longitude = filter_var($_JSON['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    
                    
                    if(isset($_JSON['google_place_id'])){
                    	$city = City::getCityFromGoogle(filter_var($_JSON['google_place_id'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES));
                    	if($city)
                    		$user->cityId = $city->id;
                    }
                    
                    if(isset($_JSON['region']))
                    	$user->region = filter_var($_JSON['region'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    
                    if(isset($_JSON['referral']) && strlen(trim($_JSON['referral'])) > 1 && empty($user->referral)){
                    	$referral = filter_var($_JSON['referral'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    	$referralUser = User::model()->findByAttributes(array('userName' => $referral));

                    	if(!is_null($referralUser) || !empty($referralUser))
                    		$user->referral = $referralUser->userName; 
                    }
                    
                    //save image
                    if(isset($_JSON['image']) && !empty($_JSON['image']))
                    {
                        $imageDir = Yii::getPathOfAlias('webroot.images.user');
                        $thumbDir = Yii::getPathOfAlias('webroot.images.user.thumb');
                        $maxWidth = 72;
                        
                        
                        $img = explode('?type=', $_JSON['image']);                     
                        if(count($img)>0){
                        	$user->image = $img[0];
                        }
                        else
                        $user->image = saveBase64ImagePng($_JSON['image'], $imageDir, $thumbDir, $maxWidth);
                    }

                    //save record
                    
                    if($isNewUser)
                    $user->role = 'user';
                    $user->scenario = 'api_signin';
                    $user->save();
                    if ($user->hasErrors()) 
                    {
                        throw new Exception(print_r($user->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    
                    if(isset($referralUser) && !is_null($referralUser) || !empty($referralUser)){
                    
                    	$sql = "insert into activityLog  ( `facebookId`, `activityType`, `elementId`, `points`) select '".$referralUser->facebookId."' , id, '".$user->id."', 500 from activityPoints where activityTable = 'user'";					
                    	Yii::app()->db->createCommand($sql)->query();

                    	$sql = "insert into activityLog  ( `facebookId`, `activityType`, `elementId`, `points`) select '".$user->facebookId."' , id, '".$user->id."', 500 from activityPoints where activityTable = 'user'";
                    	Yii::app()->db->createCommand($sql)->query();
                    
                    }
                    
                    //hack for auto pin restaurants
                    if($isNewUser){
                    	
                    	$restaurant=array(400, 1488, 545, 537, 1232, 213, 1390, 1451, 1307, 120, 323);
                    	
                    	foreach ($restaurant as $restaurantId){                    		
                    		$favourite = new Favourite('add_api');
                    		$favourite->userId = $user->id;
                    		$favourite->restaurantId = $restaurantId;
                    		//save record
                    		$favourite->save();
                    	}
                    }
                    
                    
                    
                    if(isset($_JSON['deviceToken']))
                        $deviceToken = filter_var($_JSON['deviceToken'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    if(empty($user->userName))
                        $userName = '';
                    else
                        $userName = $user->userName;
                    
                    $profile = User::getProfileById($user->id, $user->id);
                    if(is_null($profile))
                        $profile = new stdClass ();
                    
                    /*
                     * The following block is added as per client request
                     * He wants that a user should automatically follow every one on registration
                     * This feature will be temporary and added after most of the application finished
                     * If auto follow is not required, comment this part out
                     */
                    if($isNewUser && $user->email != 'ashish.june17@gmail.com' )
                        Follower::autoFollow($user->id);
                    /* end of auto follow part */
                    
                    $session = $this->login($user->id, $user->role, $deviceToken);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'User signed in successfully.',
                        'status' => 'OK',
                        'isNewUser' => $isNewUser,
                        'userId' => $user->id,
                        'userName' => $userName,
                        'profile' => $profile,
                        'sessionId' => $session->sessionId,
                    	'refreshToken' => $session->refreshToken
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
