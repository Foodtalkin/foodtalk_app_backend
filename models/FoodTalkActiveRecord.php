<?php
abstract class FoodTalkActiveRecord extends CActiveRecord
{
    //Prepares createId and updateId attributes beforesaving.
    protected function beforeSave()
    {
        if(isset(Yii::app()->user) && null !== Yii::app()->user)
            $id = Yii::app()->user->id;
        else
            $id=0;
        
        if($this->isNewRecord){
        	
        	$this->logActivity();
        	
        	if(!$this->createId)
            $this->createId = $id;
        	
        }elseif($this->isDisabled==1) {
        	$this->logActivity('delete');        	
        }
        
        $this->updateId = $id;
        $this->convertFieldsToLower();        
        
        return parent::beforeSave();
    }
    
    
    protected function beforeDelete(){
    	
    	$this->logActivity('delete');
    	return parent::beforeDelete();
    	
    }
    
    protected function logActivity($type='add'){
    	

    	$jsonInput = file_get_contents("php://input");
    	$_JSON = json_decode($jsonInput, true);
    	
    	if(!$_JSON){
    		return true;
    	}
    	
    	$activity = ActivityPoints::model()->findByAttributes(
    			array(
    					'platform'=>'app',
    					'isDisabled'=>'0', 
    					'activityTable'=>$this->tableName() 
    					
    			));
    	
    	if($activity && $this->manageActivity($activity)){
    		
    		
    		$session = Session::model()->findByAttributes(array('sessionId'=>$_JSON['sessionId']));
    		
    		$UserfacebookId = $session->user->facebookId;
    		
    		$model = new ActivityLog;
    		$criteria= new CDbCriteria;
    		$criteria->select='id, facebookId, activityType, elementId, points, isPenalized, max(createDate) createDate';
    		$criteria->condition = "facebookId = '$UserfacebookId' and isPenalized = 0 and activityType = ". $activity->id;
    		$lastActivity = $model->model('ActivityLog')->find($criteria);
    		    		
    		$activity_log = new ActivityLog('create_api');
    		$activity_log->activityType = $activity->id;
    		$activity_log->elementId = $this->id;
    		$activity_log->facebookId = $UserfacebookId;
    		
    		if($type=='delete'){
//   to	penalise    			
    			$activity_log->points = $activity->penality;
    			$activity_log->isPenalized = '1';
    			
    		}else{
    			
//   to	rationalize points as per time factor and activity

    			$date1 = strtotime($lastActivity->createDate);    			
    			$date2 = time();
    			$subTime =  $date2 - $date1;
    			
				$m = $subTime/60;

    			
    			

	    		$points = $activity->points * ( $m/$activity->timefactor );
    			
    			if($points < $activity->minimum)
    				$points = $activity->minimum;
    			
    			if($points > $activity->maximum)
					$points = $activity->maximum;
    			
	 			$activity_log->points = $points;
    		}

    		
    		
    		$activity_log->save();
    		
				if ($activity_log->hasErrors())
    				throw new Exception(print_r($activity_log->getErrors(), true), WS_ERR_UNKNOWN);
    		
    	}
    	
    	return true;
    	
    }
    
    protected function manageActivity(ActivityPoints $activity){
    
    	return true;
    }
    
    
    protected function convertFieldsToLower(){
    	
    	$fieldsToTranslate = array(
    			'categoryName',
    			'cityName',
    			'message',
    			'cuisineName',
    			'dishName',
    			'contactName',
    			'area',
    			'highlights',
    			'priceRange',
    			'timing',
    			'restaurantName',
    			'tagName',
    			'fullName',
    			'gender',
    			'country',
    			'state',
    			'city',
    			'address',
    			'aboutMe',
    			'userName'
    	);

    	foreach ($fieldsToTranslate as $field){    		
    		if(isset($this->$field ) )
    			$this->$field = strtolower( $this->$field );
    	}
//     			var_dump($this->attributes);
//     	die('BOOOOOOOOOOOOOOO!');
    }

    
    //Attaches the timestamp behavior to update our create and update times
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'createDate',
                'updateAttribute' => 'updateDate',
                'setUpdateOnCreate' => true,
            ),
        );
    }
}