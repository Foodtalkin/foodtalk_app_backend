<?php
class ServiceBaseController extends Controller
{
    const AUTH_ENABLED = true;
    
    protected function error($api, $code, $text)
    {
    	
        return array('api'=>$api, 'status'=>'error', 'errorCode'=>($code ? $code : 99), 'apiMessage'=>$text);
    }
    
    /**
     * Check if required query params exist (throws Exception)
     * @param $params
     */
    protected function checkRequired($params = array(), $checkAuthFields = true)
    {
        if (defined('self::AUTH_ENABLED') && $checkAuthFields) {
            if (!isset($params['POST'])) $params['POST'] = array();
            $params['POST']['sessionId'] = true;
        }
        
        foreach ($params as $method => $mParams) {
            foreach ($mParams as $key => $required) {
                if ($required && (!isset($GLOBALS['_'.$method][$key])
                    || $GLOBALS['_'.$method][$key] == ''))
                {
                    throw new Exception('You missed a '.$method.' parameter: '.$key.'.',
                        constant('WS_ERR_'.$method.'_PARAM_MISSED'));
                }
            }
        }
    }
    
    /**
     * Check query authorization
     * @return int User ID
     * @throws Exception
     */
    protected function isAuthentic($sessionId = null)
    {
        // Remove old sessions
//         Session::model()->deleteAll('timestamp <= DATE_SUB(NOW(), INTERVAL 1 MONTH) and sessionId != "GUEST" ');

        if (null === $sessionId) 
            $sessionId = filter_var($_POST['sessionId'], FILTER_SANITIZE_STRING);

        // Check sessionId
//         comment after one month.
        $session = Session::model()->findByAttributes(array('sessionId' => $sessionId));
//         uncomment after one month.
//         $session = Session::model()->findByAttributes(array('sessionId' => $sessionId), 'timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)' );
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

    /**
     * @param $fieldName
     * @throws Exception
     */
    protected function checkUploadedImage($fieldName)
    {
        $file = CUploadedFile::getInstanceByName($fieldName);
        if ($file != null)
        {
            switch ($file->type) 
            {
                case 'image/jpeg':
                case 'image/gif':
                case 'image/png':
                    break;
                default:
                    throw new Exception('The file has wrong mime-type:'.$filetype, WS_ERR_UNKNOWN);
            }
        }
        return $file;
    }

    /**
     * Follow previously invited user
     * @param $userId
     * @param $socialId
     * @param $type
     */
    protected function setFollowing($userId,$socialId,$type = 'facebook')
    {
        try{
            if ('facebook' === $type){
                $friendship = Friendshiprequest::model()->findAllByAttributes(array(
                        'facebookId' => $socialId
                ));
            } else if ('twitter' === $type){
                $friendship = Friendshiprequest::model()->findAllByAttributes(array(
                        'twitterId' => $socialId
                ));
            }
            
            
            foreach ($friendship as $request){
                $follow = new Following;
                $follow->userId = $request->userId;
                $follow->followingId = $userId;
    
                if($follow->save()){
                    Friendshiprequest::model()->deleteByPk($request->id);
                } else {
                    throw new Exception('Error', WS_ERR_UNKNOWN);
                }
                
            }
        } catch (Exception $e) {}
            
    }
    
    public function __construct($id, CWebModule $module = null, $jsonOutput = true)
    {

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
    
    public function getStatusMessage($status)
    {
        $statusMessages = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($statusMessages[$status])) ? $statusMessages[$status] : '';
    }

    function sendResponse($body = '', $status = 200, $contentType = 'application/json')
    {
        $statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusMessage($status);
        header($statusHeader);
        header('Content-type: ' . $contentType);
        header('Access-Control-Allow-Origin: *');
        echo $body;
    }
    
    protected function isManager($user){    	
//     	$user->role == 'manager';
    	return true;
    }
    
    function getJsonInput()
    {
    	
    	
// 		var_dump($_GET);
// 		error_log($requestType);
//     	print_r($_JSON);
		$requestType = $_SERVER['REQUEST_METHOD'];		
    	if($requestType == 'POST'){		
	        $jsonInput = file_get_contents("php://input");
    	    $_JSON = json_decode($jsonInput, 1);
    	}else{
    		$_JSON = $_GET;
    	}
     
// error_log('LOG : '. print_r( $_JSON, true ) );   
        return $_JSON;
    }
}
