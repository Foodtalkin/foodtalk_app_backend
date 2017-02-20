<?php
class RestaurantServiceBaseController extends ServiceController
{
    const AUTH_ENABLED = true;
    
    public $options = [];
    
    public $restaurant = null;
    
    protected function restaurant(){
    	return (object) $this->restaurant;
    }
    
    /**
     * Check query authorization
     * @return int User ID
     * @throws Exception
     */
    protected function isAuthentic($sessionId = null)
    {
        // Remove old sessions
        Session::model()->deleteAll('timestamp <= DATE_SUB(NOW(), INTERVAL 1 MONTH) and sessionId != "GUEST" ');

        if (null === $sessionId) 
            $sessionId = filter_var($_POST['sessionId'], FILTER_SANITIZE_STRING);

        // Check sessionId
        $session = Session::model()->findByAttributes(array('sessionId' => $sessionId));
        if (null === $session) {
            throw new Exception(
                'Your session has expired, please login again.', WS_ERR_WONG_SESSION_ID);
        }
        
//         $session->timestamp = date('Y-m-d H:i:s'); 
//         $session->save();
        
        return $session->userId;
    }

    protected function initOutput()
    {
        header('Content-type: text/json');
    }
    
    public function actionError()
    {
    	if($error=Yii::app()->errorHandler->error)
    	{
    		return	$this->sendResponse([],$error['errorCode'], $error['message']);
    	}
    }
    
    public function __construct($id, CWebModule $module = null, $jsonOutput = true)
    {
    	
    	Yii::app()->errorHandler->errorAction = $id.'/Error';
    	 
    	
    	$nonAutharizedRequest = ['auth']; 
    	
    	if(!in_array(ltrim($id, 'resto/'), $nonAutharizedRequest)){
    		$authorized = false;
    		$message = null;
    		$headers = getallheaders();
    		 
    		if (isset($headers['APISESSID'])) {
    			session_id ( $headers['APISESSID'] );
    			session_start();
    			if (isset ($_SESSION ['loginId'] )) {
    				$authorized = true;
    				
    				$this->restaurant = $_SESSION['restaurant'];
    				$this->options['restaurantId'] = $this->restaurant ()->id;
    				
    				
    			}else{
    				$message = 'Invalid/Expired Session.';
    				session_destroy();
    			}
    		}
    		if (! $authorized) {
    			$this->sendResponse([],self::UN_AUTHORIZED, 'login is required');
    			die();
    		}
    		
    	}
    	
    	$req = 	$this->getJsonInput();
    	
    	$latitude = NULL;
    	$longitude = NULL;
    	$sessionId = NULL;
    	$dump = NULL;
    	$userSQL = NULL;
    	
    	$ip_address = $_SERVER['REMOTE_ADDR'];
    	$end_point = $_SERVER['REQUEST_URI'];
    	$method = $_SERVER['REQUEST_METHOD'];
    	
    	if(isset($req['sessionId']))
    		$sessionId = $req['sessionId'];
    	
    	if(isset($req['latitude']))
    		$latitude = $req['latitude'];
    	
    	if(isset($req['longitude']))
    		$longitude = $req['longitude'];
    	
    	if($req)
    		$dump = json_encode($req);

    	$userSQL = '(select userId from session where sessionId = "'.$sessionId.'")';
    		 
    	$sql = 'insert into access_logs (ip_address, user_id, platform, end_point, latitude, longitude, dump, method) values (:ip_address, '.$userSQL.', :platform,  :end_point, :latitude, :longitude, :dump, :method)';

		$result = Yii::app()->db->createCommand($sql)->query(array('ip_address'=> $ip_address, 'platform'=> $_SERVER['HTTP_USER_AGENT'], 'end_point'=> $end_point, 'latitude'=> $latitude, 'longitude'=> $longitude, 'dump'=> $dump, 'method'=> $method));
	    

        // Init output
        if ($jsonOutput) $this->initOutput();

        parent::__construct($id, $module);
    }
    
    protected function isManager(){    	
//     	$user->role == 'manager';
    	return true;
    }
}
