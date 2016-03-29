<?php

/**
 * This is the model class for table "session".
 *
 * The followings are the available columns in table 'session':
 * @property string $id
 * @property string $sessionId
 * @property string $userId
 * @property string $userName
 * @property string $role
 * @property string $deviceToken
 * @property string $deviceBadge
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Session extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'session';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sessionId, userId, role', 'required'),
            array('sessionId', 'length', 'max'=>40),
            array('userId, deviceBadge', 'length', 'max'=>10),
            array('userName', 'length', 'max'=>128),
            array('role', 'length', 'max'=>16),
            array('deviceToken', 'length', 'max'=>500),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sessionId, userId, userName, role, deviceToken, deviceBadge, timestamp', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sessionId' => 'Session',
            'userId' => 'User',
            'userName' => 'User Name',
            'role' => 'Role',
            'deviceToken' => 'Device Token',
            'deviceBadge' => 'Device Badge',
            'timestamp' => 'Timestamp',
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
        $criteria->compare('sessionId',$this->sessionId,true);
        $criteria->compare('userId',$this->userId,true);
        $criteria->compare('userName',$this->userName,true);
        $criteria->compare('role',$this->role,true);
        $criteria->compare('deviceToken',$this->deviceToken,true);
        $criteria->compare('deviceBadge',$this->deviceBadge,true);
        $criteria->compare('timestamp',$this->timestamp,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Session the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get session records
     */
    public static function getQuery()
    {
        $sql = "SELECT s.id";
        $sql .= ",s.sessionId";
        $sql .= ",s.userId";
        $sql .= ",s.userName";
        $sql .= ",s.role";
        $sql .= ",s.deviceToken";
        $sql .= ",s.deviceBadge";
        $sql .= " FROM session s";
        
        return $sql;
    }
    
    public static function getDeviceBadge($userId)
    {
        $sql = "SELECT s.deviceBadge FROM session s WHERE s.userId=$userId";
        $session = Yii::app()->db->createCommand($sql)->queryRow(true);
        if(is_null($session) || empty($session) || empty($session['deviceBadge']))
            return 0;
        else
            return $session['deviceBadge'];
    }
}
