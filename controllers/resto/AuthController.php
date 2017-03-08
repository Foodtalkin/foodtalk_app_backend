<?php
class AuthController extends RestaurantServiceBaseController
{
	public function actionLogin()
	{
		$param  = $this->getJsonInput();
		$autharized = false;
		$response = array();
		
		if(isset($param['email']) && isset($param['password'])){
			 
			$Restaurant = Restaurant::model()->findByAttributes(array('email'=>$param['email'], 'password'=>$param['password'], 'isActivated'=>1 , 'isDisabled'=>0));
			
// 			$admin = Admin::where(array('email'=>$param['email'], 'password'=>$param['password'], 'active'=>1))->first();
			 
			if($Restaurant){
				session_start();
				$session_id = session_id();
				$response = array(
						'APISESSID'=> session_id(),
						'email'=>$Restaurant->email,
						'restaurantName'=> $Restaurant->restaurantName
				);
				$_SESSION['loginId'] = $Restaurant->id;
				$_SESSION['restaurant']['id'] = $Restaurant->id;
				$_SESSION['restaurant']['email'] = $Restaurant->email;
				$_SESSION['restaurant']['restaurantName'] = $Restaurant->restaurantName;
				$_SESSION['restaurant']['area'] = $Restaurant->area;
				$_SESSION['role'] = $Restaurant->role;
				$autharized = true;
			}
		}else
			return	$this->sendResponse($response,self::NOT_ACCEPTABLE, 'Invalid params');
		
		if(!$autharized){
			return	$this->sendResponse($response,self::UN_AUTHORIZED, 'Invalid credentials');
		}
		return	$this->sendResponse($response,self::SUCCESS_OK, 'login success');
		
	}
	
	public function actionLogout(){
		session_destroy();
		return $this->sendResponse(null,self::REQUEST_ACCEPTED, 'Logout accepted');
	
	}
}
