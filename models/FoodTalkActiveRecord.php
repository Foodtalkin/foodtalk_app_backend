<?php
abstract class FoodTalkActiveRecord extends CActiveRecord
{
	protected static $MAXRecordCount = 50;
	
    //Prepares createId and updateId attributes beforesaving.
    protected function beforeSave()
    {
        if(isset(Yii::app()->user) && null !== Yii::app()->user)
            $id = Yii::app()->user->id;
        else
            $id=0;
        
        if($this->isNewRecord && !$this->createId )
        	$this->createId = $id;
        	
        
        $this->updateId = $id;
        $this->convertFieldsToLower();        
        
        return parent::beforeSave();
    }
    
    protected function urlfy($string){
    	
    	$string = str_replace(' ', '-', $string);
    	$string = str_replace('&', 'and', $string);
    	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
    	
    	return preg_replace('/-+/', '-', $string);
    }
    
    protected function afterSave()
    {
    	
    	if($this->isNewRecord)
    		$this->logActivity();
    	
    	elseif(isset($this->isDisabled) && $this->isDisabled==1)
    		$this->logActivity('delete');    	
    	
    	return parent::afterSave();
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

    		if(isset($_JSON['sessionId'])){
    			$session = Session::model()->findByAttributes(array('sessionId'=>$_JSON['sessionId']));
    			$UserfacebookId = $session->user->facebookId;
    		}
    		elseif ($this->tableName()== 'user'){
    			$UserfacebookId = $this->facebookId;
    		}
    		
    		return ActivityLog::model()->log($UserfacebookId, $activity, $this->id, $type);    		
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
//     			'message',
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
    		if(isset($this->$field ) ){
    			
    			if(is_string($this->$field))    			
    			$this->$field = strtolower( $this->$field );
    			
    		}
    	}
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