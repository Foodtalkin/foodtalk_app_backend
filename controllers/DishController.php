<?php

class DishController extends SiteBaseController
{
	
	public $layout='//layouts/column2';
	
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionAdmin()
	{
		$model=new Dish('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dish']))
			$model->attributes=$_GET['Dish'];
	
		$this->render('admin',array(
				'model'=>$model,
		));
	}
	
	public function actionView($id)
	{
		
		if(isset($_POST['action']) && $_POST['action']=='update'){

			$dishName = trim($_POST['dishName']);
			
			$dishCheck = Dish::model()->findByAttributes(array('dishName'=>$dishName));
			
			$dishId=$id;
			
			if($dishCheck &&  $dishCheck->id!=$id)
				$dishId = $dishCheck->id;
			
			DishCuisine::model()->deleteAllByAttributes(array('dishId'=>$id ));
			
			if(isset($_POST['cuisine']))
			foreach ($_POST['cuisine'] as $cuisineId){
				$cuisine = new DishCuisine;
				$cuisine->dishId = $dishId;
				$cuisine->cuisineId = $cuisineId;
				$cuisine->save();
				if ($cuisine->hasErrors())
				{
					throw new Exception(print_r($cuisine->getErrors(), true), WS_ERR_UNKNOWN);
				}
			}
			
			
			if($dishCheck &&  $dishCheck->id!=$id){
				
				DishReview::model()->updateAll(array('dishId'=>$dishCheck->id), 'dishId ='.$id);
				Dish::model()->deleteByPk($id);
				$_SESSION['flashmsg']['success'] = 'Success update';
				$this->redirect(array('view','id'=>$dishCheck->id));
			}
			
			$dish = $this->loadModel($id);
			$dish->dishName = $dishName;
		
				
			if($dish->save()){		
				$_SESSION['flashmsg']['success'] = 'Success update';
			}
				$this->redirect(array('view','id'=>$dish->id));
		}
		
		$model=new Post('search');
    	$model->unsetAttributes();  // clear any default values
    	 
    	$this->render('view',array(
    			'model'=>$this->loadModel($id),
    			'posts'=>$model,
    			//         	'restaurant'=
    	));
	}

	public function actionDelete($id)
	{
	
		$dish = $this->loadModel($id);
		$dish->isDisabled = 1;
		$dish->disableReason = 'admin disabled';
	
		if($dish->save()){
			$_SESSION['flashmsg']['success'] = 'Dish Disabled';
		}
		$this->redirect(array('view','id'=>$dish->id));
	
	}
	
	public function actionDisable($id)
	{

		$dish = $this->loadModel($id);
		$dish->isDisabled = 1;
		$dish->disableReason = 'admin disabled';
		
		if($dish->save()){
			$_SESSION['flashmsg']['success'] = 'Dish Disabled';
		}
		$this->redirect(array('view','id'=>$dish->id));
		
	}
	public function actionRestore($id)
	{
		$dish = $this->loadModel($id);
		$dish->isDisabled = 0;
		$dish->disableReason = null;
		
		if($dish->save()){
			$_SESSION['flashmsg']['success'] = 'Dish Restored';
		}
		$this->redirect(array('view','id'=>$dish->id));
		
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
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Dish::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
}