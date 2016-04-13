<?php

/**
 * This is the model class for table "activityLog".
 *
 * The followings are the available columns in table 'activityLog':
 * @property string $id
 * @property string $facebookId
 * @property integer $activityType
 * @property integer $elementId
 * @property double $points
 * @property string $remarks
 * @property integer $isPenalized
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property ActivityPoints $activityType0
 */
class ActivityLog extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activityLog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('facebookId, activityType, points', 'required'),
			array('activityType, elementId, isPenalized', 'numerical', 'integerOnly'=>true),
			array('points', 'numerical'),
			array('facebookId', 'length', 'max'=>30),
			array('remarks', 'length', 'max'=>128),
			array('createId, updateId', 'length', 'max'=>10),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, facebookId, activityType, elementId, points, remarks, isPenalized, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'activityType0' => array(self::BELONGS_TO, 'ActivityPoints', 'activityType'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'facebookId' => 'Facebook',
			'activityType' => 'Activity Type',
			'elementId' => 'Element',
			'points' => 'Points',
			'remarks' => 'Remarks',
			'isPenalized' => 'Is Penalized',
			'createDate' => 'Create Date',
			'updateDate' => 'Update Date',
			'createId' => 'Create',
			'updateId' => 'Update',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('facebookId',$this->facebookId,true);
		$criteria->compare('activityType',$this->activityType);
		$criteria->compare('elementId',$this->elementId);
		$criteria->compare('points',$this->points);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('isPenalized',$this->isPenalized);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId,true);
		$criteria->compare('updateId',$this->updateId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function log($UserfacebookId, $activity, $elementId=null, $type='add'){
		
		if($activity instanceof ActivityPoints){
		
			$model = new self;
		
    		$criteria= new CDbCriteria;
    		$criteria->select='id, facebookId, activityType, elementId, points, isPenalized, max(createDate) createDate';
    		$criteria->condition = "facebookId = '$UserfacebookId' and isPenalized = 0 and activityType = ". $activity->id;
    		$lastActivity = $model->model('ActivityLog')->find($criteria);
    		    		
    		$activity_log = new self('create_api');
    		$activity_log->activityType = $activity->id;
    		
    		if($elementId)
	    		$activity_log->elementId = $elementId;
    		
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
    			
				if($activity->timefactor > 0)
		    		$points = $activity->points * ( $m/$activity->timefactor );
				else 
					$points = $activity->points;
    			
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivityLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
