<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property string $id
 * @property string $receiverId
 * @property integer $notificationGroup
 * @property string $message
 * @property string $eventId
 * @property integer $isSeen
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $receiver
 * @property Event $event
 */
class Notification extends FoodTalkActiveRecord
{
    const NOTIFICATION_GROUP_YOU = 1;
    const NOTIFICATION_GROUP_WORLD = 2;
    const NOTIFICATION_PUBLIC = 3;
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'notification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('receiverId, notificationGroup, message, eventId', 'required'),
            array('notificationGroup, isSeen, isDisabled', 'numerical', 'integerOnly'=>true),
            array('receiverId, eventId, createId, updateId', 'length', 'max'=>10),
            array('message', 'length', 'max'=>500),
            array('disableReason', 'length', 'max'=>128),
            array('createDate, updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, receiverId, notificationGroup, message, eventId, isSeen, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'receiver' => array(self::BELONGS_TO, 'User', 'receiverId'),
            'event' => array(self::BELONGS_TO, 'Event', 'eventId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'receiverId' => 'Receiver',
            'notificationGroup' => 'Notification Group',
            'message' => 'Message',
            'eventId' => 'Event',
            'isSeen' => 'Is Seen',
            'isDisabled' => 'Is Disabled',
            'disableReason' => 'Disable Reason',
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
        $criteria->compare('receiverId',$this->receiverId,true);
        $criteria->compare('notificationGroup',$this->notificationGroup);
        $criteria->compare('message',$this->message,true);
        $criteria->compare('eventId',$this->eventId,true);
        $criteria->compare('isSeen',$this->isSeen);
        $criteria->compare('isDisabled',$this->isDisabled);
        $criteria->compare('disableReason',$this->disableReason,true);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Notification the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get notification records
     */
    public static function getQuery()
    {
        $sql = 'SELECT n.id';
        $sql .= ',n.receiverId';
        $sql .= ',n.notificationGroup';
        $sql .= ',n.message';
        $sql .= ',n.eventId';
        $sql .= ',n.isSeen';
        $sql .= ',e.eventType';
        $sql .= ',e.raiserId';
        $sql .= ',u1.userName as raiserName';
        $sql .= ',u1.userName';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u1.image), "") as raiserImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u1.image), "") as raiserThumb';
        $sql .= ',IFNULL(e.relatedUserId, "") as relatedUserId';
        $sql .= ',IFNULL(u2.userName, "") as relatedUserName';
        $sql .= ',e.elementId';
        $sql .= ',e.eventDate';
        $sql .= ',e.isNotified';
        $sql .= ' FROM `notification` n';
        $sql .= ' JOIN `event` e ON n.eventId=e.id';
        $sql .= ' JOIN `user` u1 ON e.raiserId=u1.id';
        $sql .= ' LEFT JOIN `user` u2 ON e.relatedUserId=u2.id';
        return $sql;
    }
    
    /**
     * Returns all notification records by receiver id
     * @param type $receiverId
     * @param type $notificationGroup
     * @return type
     */
    public static function getNotificationsByReceiverId($receiverId, $notificationGroup)
    {
        //fetch all notification records
        $sql = self::getQuery();
        $sql .= ' WHERE n.receiverId =' . $receiverId;
        $sql .= ' AND n.notificationGroup =' . $notificationGroup;
        $sql .= ' ORDER BY n.createDate DESC';
        $sql .= ' limit 40';
        $notifications = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $notifications;
    }
    
    /**
     * Saves notification record and returns new notification id
     * @param type $receiverId
     * @param type $notificationGroup
     * @param type $message
     * @param type $eventId
     * @return int
     */
    public static function saveNotification($receiverId, $notificationGroup, $message, $eventId)
    {
        $notification = new Notification();
        $notification->receiverId = $receiverId;
        $notification->notificationGroup = $notificationGroup;
        $notification->message = $message;
        $notification->eventId = $eventId;
        $notification->save();
        
        if ($notification->hasErrors()) 
            return 0;
        else
            return $notification->id;
    }
}
