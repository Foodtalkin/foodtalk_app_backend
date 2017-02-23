<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ServiceController extends Controller
{
	
	const PAGE_SIZE = 100;
	const MAX_PAGE_SIZE = 150;
	
	//
	// 100 => 'Continue',
	// 101 => 'Switching Protocols',
	const SUCCESS_OK = 200; // 200 => 'OK',
	// 201 => 'Created',
	const REQUEST_ACCEPTED = 202;	// 202 => 'Accepted',
	// 203 => 'Non-Authoritative Information',
	// 204 => 'No Content',
	// 205 => 'Reset Content',
	// 206 => 'Partial Content',
	// 300 => 'Multiple Choices',
	// 301 => 'Moved Permanently',
	// 302 => 'Found',
	// 303 => 'See Other',
	// 304 => 'Not Modified',
	// 305 => 'Use Proxy',
	// 306 => '(Unused)',
	// 307 => 'Temporary Redirect',
	// 400 => 'Bad Request',
	const UN_AUTHORIZED = 401;	// 401 => 'Unauthorized',
	// 402 => 'Payment Required',
	const FORBIDDEN = 403; // 403 => 'Forbidden',
	const NO_ENTITY = 404; // 404 => 'Not Found',
	// 405 => 'Method Not Allowed',
	const NOT_ACCEPTABLE = 406; // 404 => 'Not Found',
	
	// 406 => 'Not Acceptable',
	// 407 => 'Proxy Authentication Required',
	// 408 => 'Request Timeout',
	// 409 => 'Conflict',
	// 410 => 'Gone',
	// 411 => 'Length Required',
	// 412 => 'Precondition Failed',
	// 413 => 'Request Entity Too Large',
	// 414 => 'Request-URI Too Long',
	// 415 => 'Unsupported Media Type',
	// 416 => 'Requested Range Not Satisfiable',
	// 417 => 'Expectation Failed',
	// 500 => 'Internal Server Error',
	// 501 => 'Not Implemented',
	// 502 => 'Bad Gateway',
	// 503 => 'Service Unavailable',
	// 504 => 'Gateway Timeout',
	// 505 => 'HTTP Version Not Supported'
    
	
	public function statusMessage($status)
	{
		
		$ok = 'OK';
		$error = 'ERROR';
		$statusMessages = array(
				100 => array( 'code' =>'100', 'status'=>$ok, 'header'=>'Continue', 'message'=>'Continue'),
				101 => array( 'code' =>'101', 'status'=>$ok, 'header'=>'Switching Protocols', 'message'=>'Switching Protocols'),
				200 => array( 'code' =>'200', 'status'=>$ok, 'header'=>'OK', 'message'=>'Success'),
				201 => array( 'code' =>'201', 'status'=>$ok, 'header'=>'Created', 'message'=>'Created'),
				202 => array( 'code' =>'202', 'status'=>$ok, 'header'=>'Accepted', 'message'=>'Accepted'),
				203 => array( 'code' =>'203', 'status'=>$ok, 'header'=>'Non-Authoritative Information', 'message'=>'Non-Authoritative Information'),
				204 => array( 'code' =>'204', 'status'=>$ok, 'header'=>'No Content', 'message'=>'No Content'),
				205 => array( 'code' =>'205', 'status'=>$ok, 'header'=>'Reset Content', 'message'=>'Reset Content'),
				206 => array( 'code' =>'206', 'status'=>$ok, 'header'=>'Partial Content', 'message'=>'Partial Content'),
				300 => array( 'code' =>'300', 'status'=>$ok, 'header'=>'Multiple Choices', 'message'=>'Multiple Choices'),
				301 => array( 'code' =>'301', 'status'=>$ok, 'header'=>'Moved Permanently', 'message'=>'Moved Permanently'),
				302 => array( 'code' =>'302', 'status'=>$ok, 'header'=>'Found', 'message'=>'Found'),
				303 => array( 'code' =>'303', 'status'=>$ok, 'header'=>'See Other', 'message'=>'See Other'),
				304 => array( 'code' =>'304', 'status'=>$ok, 'header'=>'Not Modified', 'message'=>'Not Modified'),
				305 => array( 'code' =>'305', 'status'=>$ok, 'header'=>'Use Proxy', 'message'=>'Use Proxy'),
				306 => array( 'code' =>'306', 'status'=>$ok, 'header'=>'(Unused)', 'message'=>'(Unused)'),
				307 => array( 'code' =>'307', 'status'=>$ok, 'header'=>'Temporary Redirect', 'message'=>'Temporary Redirect'),
				400 => array( 'code' =>'400', 'status'=>$error, 'header'=>'Bad Request', 'message'=>'Bad Request'),
				401 => array( 'code' =>'401', 'status'=>$error, 'header'=>'Unauthorized', 'message'=>'Unauthorized'),
				402 => array( 'code' =>'402', 'status'=>$error, 'header'=>'Payment Required', 'message'=>'Payment Required'),
				403 => array( 'code' =>'403', 'status'=>$error, 'header'=>'Forbidden', 'message'=>'Forbidden'),
				404 => array( 'code' =>'404', 'status'=>$error, 'header'=>'Not Found', 'message'=>'Not Found'),
				405 => array( 'code' =>'405', 'status'=>$error, 'header'=>'Method Not Allowed', 'message'=>'Method Not Allowed'),
				406 => array( 'code' =>'406', 'status'=>$error, 'header'=>'Not Acceptable', 'message'=>'Not Acceptable'),
				407 => array( 'code' =>'407', 'status'=>$error, 'header'=>'Proxy Authentication Required', 'message'=>'Proxy Authentication Required'),
				408 => array( 'code' =>'408', 'status'=>$error, 'header'=>'Request Timeout', 'message'=>'Request Timeout'),
				409 => array( 'code' =>'409', 'status'=>$error, 'header'=>'Conflict', 'message'=>'Conflict'),
				410 => array( 'code' =>'410', 'status'=>$error, 'header'=>'Gone', 'message'=>'Gone'),
				411 => array( 'code' =>'411', 'status'=>$error, 'header'=>'Length Required', 'message'=>'Length Required'),
				412 => array( 'code' =>'412', 'status'=>$error, 'header'=>'Precondition Failed', 'message'=>'Precondition Failed'),
				413 => array( 'code' =>'413', 'status'=>$error, 'header'=>'Request Entity Too Large', 'message'=>'Request Entity Too Large'),
				414 => array( 'code' =>'414', 'status'=>$error, 'header'=>'Request-URI Too Long', 'message'=>'Request-URI Too Long'),
				415 => array( 'code' =>'415', 'status'=>$error, 'header'=>'Unsupported Media Type', 'message'=>'Unsupported Media Type'),
				416 => array( 'code' =>'416', 'status'=>$error, 'header'=>'Requested Range Not Satisfiable', 'message'=>'Requested Range Not Satisfiable'),
				417 => array( 'code' =>'417', 'status'=>$error, 'header'=>'Expectation Failed', 'message'=>'Expectation Failed'),
				500 => array( 'code' =>'500', 'status'=>$error, 'header'=>'Internal Server Error', 'message'=>'Internal Server Error'),
				501 => array( 'code' =>'501', 'status'=>$error, 'header'=>'Not Implemented', 'message'=>'Not Implemented'),
				502 => array( 'code' =>'502', 'status'=>$error, 'header'=>'Bad Gateway', 'message'=>'Bad Gateway'),
				503 => array( 'code' =>'503', 'status'=>$error, 'header'=>'Service Unavailable', 'message'=>'Service Unavailable'),
				504 => array( 'code' =>'504', 'status'=>$error, 'header'=>'Gateway Timeout', 'message'=>'Gateway Timeout'),
				505 => array( 'code' =>'505', 'status'=>$error, 'header'=>'HTTP Version Not Supported', 'message'=>'HTTP Version Not Supported')
		);
	
		return (isset($statusMessages[$status])) ? $statusMessages[$status] : array( 'code' =>'500', 'status'=>$error, 'header'=>'Internal Server Error', 'message'=>'Internal Server Error');
	}
	
	private static final function ssstatusMessage($option) {
		$status = array ();
		$ok = 'OK';
		$error = 'ERROR';
	
		switch ($option) {
				
			case self::SUCCESS_OK :
				$status ['message'] = 'Success';
				$status ['status'] = $ok;
				$status ['code'] = '200';
				break;
	
			case self::REQUEST_ACCEPTED :
				$status ['message'] = 'Request Accepted';
				$status ['status'] = $ok;
				$status ['code'] = '202';
				break;
					
			case self::UN_AUTHORIZED :
				$status ['message'] = 'Login is Required!';
				$status ['status'] = $error;
				$status ['code'] = '401';
				break;
	
			case self::FORBIDDEN :
				$status ['message'] = 'Forbidden Access!';
				$status ['status'] = $error;
				$status ['code'] = '403';
				break;
	
			case self::NO_ENTITY :
				$status ['message'] = 'No Such Entity!';
				$status ['status'] = $error;
				$status ['code'] = '404';
				break;
	
			case self::NOT_ACCEPTABLE :
				$status ['message'] = 'Unacceptable values';
				$status ['status'] = $error;
				$status ['code'] = '406';
				break;
	
					
			default :
				$status ['message'] = 'Success';
				$status ['status'] = $ok;
				$status ['code'] = '200';
				break;
		}
		return $status;
	}
	
    
    public static final function sendResponse($responseData, $status = self::SUCCESS_OK, $message = null, $contentType = 'application/json') {
    
    	if(is_array($responseData))
    		array_walk_recursive($responseData,'toJson');
    
    	$response = array ();
    
    	
    	$response = self::statusMessage ( $status );
    	if (! is_null ( $message )) {
    		$response ['message'] = $message;
    	}
    	$response ['result'] = $responseData;
    	
//     	if (! empty ( $responseData ) && $status == self::SUCCESS_OK) {
    			
//     		$response = self::statusMessage ( $status );
//     		if (! is_null ( $message )) {
//     			$response ['message'] = $message;
//     		}
//     		$response ['result'] = $responseData;
//     	} else {
    			
//     		if ($status != self::SUCCESS_OK) {
//     			$response = self::statusMessage ( $status );
//     		} else {
//     			$response = self::statusMessage ( self::NO_ENTITY );
//     		}
    			
//     		if (! is_null ( $message )) {
//     			$response ['message'] = $message;
//     		}
//     		$response ['result'] = $responseData;
//     	}
    	
    	$statusHeader = 'HTTP/1.1 ' . $status . ' ' . $response['header'];
    	header($statusHeader);
    	header('Content-type: ' . $contentType);
    	header('Access-Control-Allow-Origin: *');
    	
    	unset($response ['header']);
    	$body = json_encode($response , JSON_UNESCAPED_UNICODE);
    	 
		if(isset($_GET['callback']) && !empty(trim($_GET['callback'])))
    		echo $_GET['callback']."($body)";
    	else 	
	    	echo $body;
    }
    
    function getJsonInput(){
    	$requestType = $_SERVER['REQUEST_METHOD'];
    	if($requestType == 'POST'){
    		$jsonInput = file_get_contents("php://input");
    		$_JSON = json_decode($jsonInput, 1);
    	}else{
    		$_JSON = $_GET;
    	}
    	return $_JSON;
    }
}