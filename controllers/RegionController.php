<?php
class RegionController extends SiteBaseController
{
	public $layout='//layouts/column2';
	
	public function actionIndex()
	{
		unset($_SESSION['region']);
		
		$referral_url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : array('user/admin') ;		
		$this->redirect($referral_url);
		
	}

	public function actionView($id=false)
	{
		
		$region = Region::model()->findByPk($id);
		
		if($region)
			$_SESSION['region'] = $region->name; 
		else 
			unset($_SESSION['region']);
// http://localhost/foodtalk/index.php/region/view/id/delhisas
		$referral_url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : array('user/admin') ;		
		$this->redirect($referral_url);
	}
	
	
	
	public function loadModel($id)
	{
		$model=Event::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}