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
	
	public function actionRest()
	{
		unset($_SESSION['region']);
	
		$referral_url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : array('user/admin') ;
		$this->redirect($referral_url);
	
	}

	public function actionView($id=false)
	{
		
		if ($id=='rest'){
			$_SESSION['region']['id'] = $id;
			$_SESSION['region']['name'] = $id;
		}else{
		
			$region = Region::model()->findByPk($id);
			
			if($region){
				$_SESSION['region']['id'] = $region->id;
				$_SESSION['region']['name'] = $region->name;			
			}
			else
				unset($_SESSION['region']);
		}
// http://localhost/foodtalk/index.php/region/view/id/delhisas
		$referral_url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : array('user/admin') ;		
		$this->redirect($referral_url);
	}

	public function actionGet($id)
	{
		if(isset($_POST['google_place_id'])){
			$city =	City::getCityFromGoogle($_POST['google_place_id']);
			$city->regionId = $id;
			$city->save();
		}
		
		$model=new City('search');
		$model->unsetAttributes();  // clear any default values
		
		$this->render('view',array(
				'model'=>$this->loadModel($id),
				'cities'=>$model,
		));
	}
	
	
	public function actionCreate()
	{
		$model=new Region;
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['Region']))
		{
			$model->attributes=$_POST['Region'];
			if($model->save())
				$this->redirect(array('get','id'=>$model->id));
		}
	
		$this->render('create',array(
				'model'=>$model,
		));
	}
	
	public function loadModel($id)
	{
		$model=Region::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	
	public function actionAdmin()
	{
		$model=new Region('search');
		$model->unsetAttributes();  // clear any default values
// 		if(isset($_GET['Dish']))
// 			$model->attributes=$_GET['Dish'];
	
		$this->render('admin',array(
				'model'=>$model,
		));
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