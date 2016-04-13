<?php

class RestaurantController extends SiteBaseController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
    	
    	
    	if(isset($_POST['action']) && $_POST['action']=='update'){
    	
    		$restaurant = $this->loadModel($id);
    			
   			if(isset($_POST['status'])){				
    				
   				if($_POST['status'] == 'duplicate' && $_POST['duplicateId'] > 0){   					
    				
   					
   					$restaurant->duplicateId = $_POST['duplicateId'];
   					$restaurant->isDisabled = 1;
   					$restaurant->isActivated = 0;
   					
   					Checkins::model()->clean($restaurant, 'duplicate');
    					
   				}else{
    					
   					$restaurant->duplicateId = null;;
   					
   					if($_POST['status'] == 'active'){    							
   						$restaurant->isActivated = 1;
   						$restaurant->isDisabled = 0;

   					}
   					if($_POST['status'] == 'inactive'){    					
   						$restaurant->isActivated = 0;
   						$restaurant->isDisabled = 0;

   					}
   					if($_POST['status'] == 'disabled'){    					
   						$restaurant->isActivated = 0;
   						$restaurant->isDisabled = 1;

   					}
   				}
   			}
   			
   			if(isset($_POST['verified'])  && $_POST['verified']=='verified'){
   				$restaurant->verified = 1;
   			}else {
   				$restaurant->verified = 0;
   			}
   			
    		$restaurant->save();
    	}
    	
    	
    	
    	
    	$model=new Post('search');
    	$model->unsetAttributes();  // clear any default values
    	
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        	'posts'=>$model,
//         	'restaurant'=	
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Restaurant;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Restaurant']))
        {
            $model->attributes=array_map('trim', $_POST['Restaurant']);
            
            $image=CUploadedFile::getInstance($model,'image');
            //set image name to current timestamp
            if($image !== null)
            {
                $imageDir = Yii::getPathOfAlias('webroot.images.restaurant');
                $thumbDir = Yii::getPathOfAlias('webroot.images.restaurant.thumb');
                
                $time = microtime(true);
                $micro = sprintf("%06d",($time - floor($time)) * 1000000);
                $date = new DateTime(date('Y-m-d H:i:s.'.$micro, $time));
                $model->image=$date->format("YmdHisu") . '.' . $image->extensionName;
                
                $image->saveAs($imageDir . '/' . $model->image);
                
                $maxWidth = 160;
                $thumb = new VgThumb($imageDir . '/' . $model->image);
                $thumb->setDestination($thumbDir);
                $thumb->setMaxSize($maxWidth);
                $thumb->create();
            }
            
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

//         echo "<h1>ASDASD</h1>";
        
        if(isset($_POST['Restaurant']))
        {
        	
            $model->attributes = array_map('trim', $_POST['Restaurant']);
            
            $image=CUploadedFile::getInstance($model,'image');
            //set image name to current timestamp
            if($image !== null)
            {
                $imageDir = Yii::getPathOfAlias('webroot.images.restaurant');
                $thumbDir = Yii::getPathOfAlias('webroot.images.restaurant.thumb');
                
                //delete old files
                if(!empty($model->image))
                {
                    unlink($imageDir . '/' . $model->image);
                    unlink($thumbDir . '/' . $model->image);
                }
                
                $time = microtime(true);
                $micro = sprintf("%06d",($time - floor($time)) * 1000000);
                $date = new DateTime(date('Y-m-d H:i:s.'.$micro, $time));
                $model->image=$date->format("YmdHisu") . '.' . $image->extensionName;
                
                $image->saveAs($imageDir . '/' . $model->image);
                
                $maxWidth = 160;
                $thumb = new VgThumb($imageDir . '/' . $model->image);
                $thumb->setDestination($thumbDir);
                $thumb->setMaxSize($maxWidth);
                $thumb->create();
            }
            
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
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

    	$model=$this->loadModel($id);
    	$model->isDisabled = 1;
    	$model->save();
//         $model = $this->loadModel($id);
//         $model->isDisabled = 1;
//         $model->updateDate = new CDbExpression("NOW()");
//         $model->updateId = Yii::app()->user->id;
//         $model->update(array('isDeleted', 'updateDate', 'updateId'));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * Restores a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionRestore($id)
    {
        $model = $this->loadModel($id);
        $model->isDisabled = 0;
        $model->updateDate = new CDbExpression("NOW()");
        $model->updateId = Yii::app()->user->id;
        $model->update(array('isDeleted', 'updateDate', 'updateId'));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('Restaurant');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    
    protected function admin()
    {
    	$model=new Restaurant('search');
    	$model->unsetAttributes();  // clear any default values
    	if(isset($_GET['Restaurant']))
    		$model->attributes=$_GET['Restaurant'];
    
    	$this->render('admin',array(
    			'model'=>$model,
    	));
    }
    
    
    
    public function actionVerified()
    {
    	$this->admin();
    }
    

//     'restaurant.unverified',
//     'restaurant.inactive',
    
    public function actionUnverified()
    {
    	$this->admin();
    }
    
    public function actionInactive()
    {
    	$this->admin();
    }
    
    public function actionDuplicate()
    {
    	$this->admin();
    }
    
    
    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Restaurant('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Restaurant']))
            $model->attributes=$_GET['Restaurant'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function actionDisabled()
    {
    	$model=new Restaurant('search');
    	$model->unsetAttributes();  // clear any default values
    	if(isset($_GET['Restaurant']))
    		$model->attributes=$_GET['Restaurant'];
    
    	$this->render('admin',array(
    			'model'=>$model,
    	));
    }
    
    public function actionReported()
    {
    	$model=new Restaurant('search');
    	$model->unsetAttributes();  // clear any default values
    	if(isset($_GET['Restaurant']))
    		$model->attributes=$_GET['Restaurant'];
    
    	$this->render('admin',array(
    			'model'=>$model,
    	));
    }
    
    public function actionSuggestion($id=false, $action=false)
    {
    	
    
    	if($id && $action == 'done'){
    		
        	$model=RestaurantSuggestion::model()->findByPk($id);
    		$model->isDisabled = 1;
    		$model->save();
    		
    		return true;
    	}
    	
    	$model=new Restaurant('search');
    	$model->unsetAttributes();  // clear any default values
    	if(isset($_GET['Restaurant']))
    		$model->attributes=$_GET['Restaurant'];
    
    	$this->render('suggestionadmin',array(
    			'model'=>$model,
    	));
    }
    
    
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Restaurant the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Restaurant::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Restaurant $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='restaurant-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
