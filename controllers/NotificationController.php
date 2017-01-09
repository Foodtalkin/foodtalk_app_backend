<?php

class NotificationController extends SiteBaseController
{
	public $layout='//layouts/column2';
	
	public function actionIndex($id=false)
	{
		$this->render('index');
	}

	public function actionView($id=false)
	{
			$model = $this->loadModel($id);
		
		if(isset($_POST['Event']) && $_POST['action']=='update')
		{
			
			$model->region= $_POST['Event']['region'];
			
			$model->eventType = $_POST['Event']['eventType'];
			$model->message = $_POST['Event']['message'];
			$model->eventDate = $_POST['Event']['eventDate'];
			$model->save();
			$_SESSION['flashmsg']['success'] = 'Success update';
			
		}
		
		$this->render('index', array(
				'view'=>'_view',
				'model'=>$model,
		));
	}
	
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// 		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	
		$model=$this->loadModel($id);	
		
		if((boolean)$model->isNotified){
			echo 'Error! Cannot delete, notification already pushed';
		}else
			$model->delete();
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
	}
	
	
	
	public function actionCreate($id=false)
	{
		
		$model = new Event();
// 			print_r($_POST);
		
		if(isset($_POST['Event']))
		{
			
	        $model->eventType = $_POST['Event']['eventType'];
	        
	        if($_POST['Event']['eventType'] == 53 || $_POST['Event']['eventType'] == 57 || $_POST['Event']['eventType'] == 58)
	        if($_POST['Event']['elementId'] > 0)	
		    	$model->elementId = $_POST['Event']['elementId'];
	        
// 	        $model->raiserId = $raiserId;

	        $model->region= $_POST['Event']['region'];
	         
	        $model->message = $_POST['Event']['message'];
	        $model->eventDate = $_POST['Event']['eventDate'];
	        $model->channel = $_POST['Event']['channel'];
	        
	        $model->save();
        
// 	        $model->hasErrors(); 
			
// 			Event::saveEvent(Event::POST_CREATED, $_POST['Post']['userId'], $model->id, $model->createDate);
			
			$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('index',array(
				'view'=>'_new',
				'model'=>$model
		));
	}
	
	public function actionAdmin()
	{
		$this->search();
	}
	
	public function actionPending()
	{
		$this->search();
	}
	
	public function actionNotified()
	{
		$this->search();
	}

	protected function search()
	{
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		
		// 		if(isset($_GET['Post']))
			// 			$model->attributes=$_GET['Post'];
		
			$this->render('index',array(
					'view'=>'_admin',
					'model'=>$model,
			));
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