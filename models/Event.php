<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property string $id
 * @property integer $eventType
 * @property string $raiserId
 * @property string $relatedUserId
 * @property string $elementId
 * @property string $eventDate
 * @property integer $isNotified
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $raiser
 * @property User $relatedUser
 * @property Notification[] $notifications
 */
class Event extends FoodTalkActiveRecord
{
    const POST_CREATED = 1;
    const POST_LIKED = 2;
    const POST_FLAGGED = 3;
    const COMMENT_CREATED = 4;
    const USER_FOLLOWED = 5;
    const RESTAURANT_MARKED_FAVOURITE = 6;
    const USER_DISABLED = 7;
    const USER_ENABLED = 8;
    const USER_MENTIONED_COMMENT = 9;
    const USER_MENTIONED_POST = 10;
    
    const HOME_FEED = 50;
    const DISCOVER_PAGE = 51;
    const USER_PROFILE = 52;
    const RESTAURANT_PROFILE = 53;
    
//     const USER_PROFILE = 52;
//     	  userMentioned
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'event';
    }

    

    public function getEventsType($type='public')
    {
    	
//     	$eclass =EventClass::model()->findByAttributes(array('tagName'=>$hashtag));
    	 
    	$events = array();

    	$publicEvent = array(
    			array('name'=>'Home screen', 'value'=>Event::HOME_FEED),
    			array('name'=>'Discover screen', 'value'=>Event::DISCOVER_PAGE),
    			array('name'=>'User Profile', 'value'=>Event::USER_PROFILE),
    			array('name'=>'Restaurant Profile', 'value'=>Event::RESTAURANT_PROFILE),
    	);
     	
    	$events = $publicEvent;
    	
    	return $publicEvent;
    }
    
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('eventType, eventDate', 'required'),
            array('eventType, isNotified', 'numerical', 'integerOnly'=>true),
            array('raiserId, relatedUserId, elementId, createId, updateId', 'length', 'max'=>10),
            array('createDate, updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, eventType, raiserId, relatedUserId, elementId, eventDate, isNotified, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'raiser' => array(self::BELONGS_TO, 'User', 'raiserId'),
        	'appClass' => array(self::BELONGS_TO, 'eventClass', 'eventType'),
            'relatedUser' => array(self::BELONGS_TO, 'User', 'relatedUserId'),
            'notifications' => array(self::HAS_MANY, 'Notification', 'eventId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'eventType' => 'Event Type',
            'raiserId' => 'Raiser',
            'relatedUserId' => 'Related User',
            'elementId' => 'Element',
            'eventDate' => 'Event Date',
            'isNotified' => 'Is Notified',
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
    public function search($type)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('eventType',$this->eventType);
        $criteria->compare('raiserId',$this->raiserId,true);
        $criteria->compare('relatedUserId',$this->relatedUserId,true);
        $criteria->compare('elementId',$this->elementId,true);
        $criteria->compare('eventDate',$this->eventDate,true);
        $criteria->compare('isNotified',$this->isNotified);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        
        
        $action = Yii::app()->controller->action->id;
        
        if($action=='notified'){
        	$criteria->addCondition("isNotified =1");
        }
        
        if($action=='pending'){
        	$criteria->addCondition("isNotified =0");
        }
        
        $criteria->addCondition("raiserId is null");
        
//         $criteria->addCondition("t.isDisabled = 1");
        
        $order = 'createDate desc';
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        	'sort'=>array(
        				'defaultOrder'=>$order
        	)
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Event the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get event records
     */
    public static function getQuery()
    {
        $sql = 'SELECT e.id';
        $sql .= ',e.eventType';
        $sql .= ',e.raiserId';
        $sql .= ',e.message';
        $sql .= ',u1.userName as raiserName';
        $sql .= ',IFNULL(e.relatedUserId, "") as relatedUserId';
        $sql .= ',IFNULL(u2.userName, "") as relatedUserName';
        $sql .= ',IFNULL(e.elementId, "0") as elementId';
//         $sql .= ',e.elementId';
        $sql .= ',e.eventDate';
        $sql .= ',e.isNotified';
        $sql .= ',e.channel';
        $sql .= ',e.region';
        
        $sql .= ',c.eventGroup';
        $sql .= ',c.defaultMessage';
        $sql .= ',c.className';
        
        $sql .= ' FROM `event` e';
        $sql .= ' INNER JOIN `eventClass` c ON e.eventType=c.id and c.isDisabled = 0 ';
        $sql .= ' LEFT JOIN `user` u1 ON e.raiserId=u1.id';
        $sql .= ' LEFT JOIN `user` u2 ON e.relatedUserId=u2.id';
        return $sql;
    }
    
    /**
     * Returns all events as per notification status
     * @param type $isNotified
     * @return type
     */
    public static function getEvents($isNotified)
    {
        //fetch all event records
        $sql = self::getQuery();
        $sql .= ' WHERE e.eventDate < now() and e.isNotified =' . $isNotified;
        $sql .= ' ORDER BY e.createDate ASC';
        
        $events = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $events;
    }
    
    /**
     * Saves event record and returns new event id
     * @param type $eventType
     * @param type $raiserId
     * @param type $elementId
     * @param type $eventDate
     * @param type $relatedUserId
     * @return int
     */
    public static function saveEvent($eventType, $raiserId, $elementId, $eventDate, $relatedUserId=0, $message = false)
    {
        $event = new Event();
        $event->eventType = $eventType;
        $event->raiserId = $raiserId;
        $event->elementId = $elementId;
        $event->eventDate = $eventDate;
        
        if($message and strlen($message)>0)
        	$event->message = trim($message);
        
        $dontSave = false;
        
        if($raiserId && $relatedUserId == $raiserId){
        	$dontSave = true;
        }
        
        if($relatedUserId)
            $event->relatedUserId = $relatedUserId;
        
        
        if($dontSave){
        	return 1;
        }
        
        $event->save();
        
        if ($event->hasErrors()) 
            return 0;
        else
            return $event->id;
    }
}
