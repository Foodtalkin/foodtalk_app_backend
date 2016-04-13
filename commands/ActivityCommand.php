<?php
class ActivityCommand extends CConsoleCommand
{
	
	public function init()
	{
		parent::init();
	
		Yii::app()->attachEventHandler('onError',array($this,'handleError'));
		Yii::app()->attachEventHandler('onException',array($this,'handleError'));
	}
	
	
	public function actionIndex($data='DEFALUT') {		
		
		echo 'index data : '.$data ."\n";
	}
	
	
	public function actionLog($facebookId, $activityId, $elementId=null, $type = 'add'){
		
		$activity = ActivityPoints::model()->findByPk($activityId);
		
		if($activity){
			ActivityLog::model()->log($facebookId, $activity, $elementId, $type);			
		}
// 		echo 'test data : '.$data ."\n";
	}
	

}
