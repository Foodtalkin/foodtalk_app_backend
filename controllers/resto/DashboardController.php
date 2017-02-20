<?php
class DashboardController extends RestaurantServiceBaseController
{
	public function actionindex()
	{
		$param  = $this->getJsonInput();
		$autharized = false;
		$response = array();
		
// 		throw new Exception(			'Your session has expired, please login again.', WS_ERR_WONG_SESSION_ID);
// 		qip59ubu42lb7r7qetnutgtve5
		
		
		print_r($this->restaurant()->restaurantName);
// 		echo $this->restaurant();
		
		return	$this->sendResponse($response,self::SUCCESS_OK, 'login success');
		
	}
	
	
	public function actionCheckins(){

		$days = 7;
		$to = date('Y-m-d H:i:s');
		$from = false;
		
		$param  = $this->getJsonInput();
		if(isset($_JSON['days']) && !empty($_JSON['days']))
			$days = filter_var($_JSON['days'], FILTER_SANITIZE_NUMBER_INT);
		
		if(isset($_JSON['from']) && !empty($_JSON['from']))
			$days = filter_var($_JSON['from'], FILTER_SANITIZE_STRING);

		if(isset($_JSON['to']) && !empty($_JSON['to']))
			$days = filter_var($_JSON['to'], FILTER_SANITIZE_STRING);
		
		$result = Restaurant::getCheckinsStats($this->restaurant->id, $days, $to, $from);
		return	$this->sendResponse($result,self::SUCCESS_OK);
	
	}
	
	public function actionLogout(){
		session_destroy();
		return $this->sendResponse([],self::REQUEST_ACCEPTED, 'Logout accepted');
	
	}
}
