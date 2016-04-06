<?php

class PostController extends SiteBaseController
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

			$checkinAction = false;
			
			$post = $this->loadModel($id);
			$post->tip = trim($_POST['tip']);
			if($post->isDisabled != $_POST['isDisabled']){
				
				if($_POST['isDisabled'] == '1')
					$checkinAction = 'disabled';
				else 
					$checkinAction = 'enabled';
			}
			$post->isDisabled = $_POST['isDisabled'];
			
			
			
			
			if(isset($_POST['checkedin']) && $_POST['checkedin'] == 'on'){
				
				if($post->checkedInRestaurantId != $_POST['checkedInRestaurantId'] && !$checkinAction){
					$checkinAction = 'changerestaurant';
				}
				$post->checkedInRestaurantId = $_POST['checkedInRestaurantId'];
				
			}else{
				$post->checkedInRestaurantId = null;
				$post->checkinId = null;
			}
			

			
			

			
// 			echo $checkinAction;
// 			$checkin = Checkins::model()->findByAttributes(array('userId'=>$post->userId,'restaurantId'=>$_POST['checkedInRestaurantId']), "DATE_FORMAT(createDate,'%m-%d-%Y') = DATE_FORMAT('".$post->createDate."','%m-%d-%Y')");
// 			if(!$checkin){
// 				$checkin = new Checkins();
// 				$checkin->userId = $post->userId;
// 				$checkin->createDate = $post->createDate;
// 				$checkin->restaurantId =  filter_var($_POST['checkedInRestaurantId'], FILTER_SANITIZE_NUMBER_INT);
					
// 				$checkin->save();
// 				if ($checkin->hasErrors())
// 				{
// 					throw new Exception(print_r($checkin->getErrors(), true), WS_ERR_UNKNOWN);
// 				}
// 			}
// 			$post->checkinId = $checkin->id;				
			
// 			die();
			
			if($post->save()){
				
				Checkins::model()->clean($post, $checkinAction);
				
				DishReview::model()->deleteAllByAttributes(array('postId'=>$id));
				TagMap::model()->deleteAllByAttributes(array('postId'=>$id));
				
				$dishReview = new DishReview('create_api');
				$dishReview->postId = $post->id;
				
				if(isset($_POST['rating']) && $_POST['rating']>0){
					$dishReview->rating = filter_var($_POST['rating'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
				}
				
				if(isset($_POST['dishName'])){
					$dish = Dish::getDishByNmae($_POST['dishName']);
					$dishReview->dishId = $dish->id;
					$dishReview->save();
				}
				
				if(!empty($post->tip))
				{
					$hashtags = getHashtags($post->tip);
					foreach($hashtags as $hashtag)
					{
						//find if the hashtag exists
						$tag = Tag::model()->findByAttributes(array('tagName'=>$hashtag));
						if(is_null($tag))
						{
							$tag = new Tag('api_insert');
							$tag->tagName = $hashtag;
							$tag->save();
						}
				
						//if the tag exists or created
						if($tag->id)
						{
							//find if the tagmap exists
							$tagMap = TagMap::model()->findByAttributes(array('tagId'=>$tag->id, 'postId'=>$post->id));
							if(is_null($tagMap))
							{
								$tagMap = new TagMap('api_insert');
								$tagMap->tagId = $tag->id;
								$tagMap->postId = $post->id;
								$tagMap->save();
							}
						}
					}
				}
				$_SESSION['flashmsg']['success'] = 'Success update';
				$this->redirect(array('view','id'=>$post->id));
			}
			
		}
		
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
		$model=new Post;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Post']))
		{	
			$_POST['Post']['image'] = uploadImagetoCloud($_POST['Post']['image']);	
			
			if (is_numeric($_POST['Post']['checkedInRestaurantId']) && $_POST['Post']['checkedInRestaurantId']>0){
				
// 				$checkin = Checkins::model()->findByAttributes(array('userId'=>$_POST['Post']['userId'],'restaurantId'=>$_POST['Post']['checkedInRestaurantId']), "DATE_FORMAT(createDate,'%m-%d-%Y') = DATE_FORMAT(NOW(),'%m-%d-%Y')");
				
				$checkin = Checkins::model()->getCheckin($_POST['Post']['userId'],$_POST['Post']['checkedInRestaurantId']);
				
// 				if(!$checkin){
// 					$checkin = new Checkins();
// 					$checkin->userId = $_POST['Post']['userId'];
// 					$checkin->restaurantId =  filter_var($_POST['Post']['checkedInRestaurantId'], FILTER_SANITIZE_NUMBER_INT);
						
// 					$checkin->save();
// 					if ($checkin->hasErrors())
// 					{
// 						throw new Exception(print_r($checkin->getErrors(), true), WS_ERR_UNKNOWN);
// 					}
// 				}
// 				$post->checkinId = $checkin->id;
				$model->checkinId = $checkin->id;
			}			
			$model->attributes=$_POST['Post'];
			
			if($model->save()){
				
				
				$dishReview = new DishReview('create_api');
				$dishReview->postId = $model->id;
				
				if(isset($_POST['rating']) && $_POST['rating']>0){
					$dishReview->rating = filter_var($_POST['rating'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
				}
				
				if(isset($_POST['dishName'])){
					$dish = Dish::getDishByNmae($_POST['dishName']);
					$dishReview->dishId = $dish->id;
					$dishReview->save();
				}
				
				if(!empty($model->tip))
				{
					$hashtags = getHashtags($model->tip);
					foreach($hashtags as $hashtag)
					{
						//find if the hashtag exists
						$tag = Tag::model()->findByAttributes(array('tagName'=>$hashtag));
						if(is_null($tag))
						{
							$tag = new Tag('api_insert');
							$tag->tagName = $hashtag;
							$tag->save();
						}
				
						//if the tag exists or created
						if($tag->id)
						{
							//find if the tagmap exists
							$tagMap = TagMap::model()->findByAttributes(array('tagId'=>$tag->id, 'postId'=>$model->id));
							if(is_null($tagMap))
							{
								$tagMap = new TagMap('api_insert');
								$tagMap->tagId = $tag->id;
								$tagMap->postId = $model->id;
								$tagMap->save();
							}
						}
					}
				}
				
				Event::saveEvent(Event::POST_CREATED, $_POST['Post']['userId'], $model->id, $model->createDate);				
				$this->redirect(array('view','id'=>$model->id));
			}
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
		die('Why you are hear, Jim is dead!!');
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
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
// 		$this->loadModel($id)->delete();

		$model=$this->loadModel($id);
		$model->isDisabled = 1;
		$model->save();
		Checkins::model()->clean($model, 'disabled');
		
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionApprove($id)
	{
		// 		$this->loadModel($id)->delete();

		$flags = Flag::model()->deleteAllByAttributes(array('postId'=>$id));
		
// 		$model=$this->loadModel($id);
// 		$model->isDisabled = 1;
// 		$model->save();
	
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Post');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionDisabled()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];
	
		$this->render('admin',array(
				'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Post the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Post::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Post $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
