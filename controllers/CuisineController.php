<?php

class CuisineController extends SiteBaseController
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
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

    	 
        $model=new Cuisine;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Cuisine']))
        {
            $model->attributes=$_POST['Cuisine'];
            
            $image=CUploadedFile::getInstance($model,'image');
            //set image name to current timestamp
            if($image !== null)
            {
                $imageDir = Yii::getPathOfAlias('webroot.images.cuisine');
                $thumbDir = Yii::getPathOfAlias('webroot.images.cuisine.thumb');
                
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
            
            if($model->save()){
            	$_SESSION['flashmsg']['success'] = 'Success';
                $this->redirect('admin');
            }
            else 
            	$this->redirect('admin');
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

        if(isset($_POST['Cuisine']))
        {
            $model->attributes=$_POST['Cuisine'];
            
            $image=CUploadedFile::getInstance($model,'image');
            //set image name to current timestamp
            if($image !== null)
            {
                $imageDir = Yii::getPathOfAlias('webroot.images.cuisine');
                $thumbDir = Yii::getPathOfAlias('webroot.images.cuisine.thumb');
                
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
     * Removes a particular dish.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be removed
     */
    public function actionremovedish($dishId, $cuisineId)
    {
    	
    	DishCuisine::model()->deleteAllByAttributes(array('dishId' =>$dishId, 'cuisineId'=> $cuisineId));
//     	$model = $this->loadModel($id);
//     	$model->isDisabled = 1;
//     	$model->updateDate = new CDbExpression("NOW()");
//     	$model->updateId = Yii::app()->user->id;
//     	$model->update(array('isDeleted', 'updateDate', 'updateId'));
    
    	// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    	if(!isset($_GET['ajax']))
    		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        
        $model->delete();        
	    DishCuisine::model()->deleteAllByAttributes(array('cuisineId'=>$id));

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
        $dataProvider=new CActiveDataProvider('Cuisine');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Cuisine('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Cuisine']))
            $model->attributes=$_GET['Cuisine'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Cuisine the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Cuisine::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Cuisine $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='cuisine-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
