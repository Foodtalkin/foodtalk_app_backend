<?php

/**
 * This is the model class for table "follower".
 *
 * The followings are the available columns in table 'follower':
 * @property string $id
 * @property string $followerUserId
 * @property string $followedUserId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $followerUser
 * @property User $followedUser
 */
class Follower extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'follower';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('followerUserId, followedUserId', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('followerUserId, followedUserId, createId, updateId', 'length', 'max'=>10),
            array('disableReason', 'length', 'max'=>128),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, followerUserId, followedUserId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'followerUser' => array(self::BELONGS_TO, 'User', 'followerUserId'),
            'followedUser' => array(self::BELONGS_TO, 'User', 'followedUserId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'followerUserId' => 'Follower User',
            'followedUserId' => 'Followed User',
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
        $criteria->compare('followerUserId',$this->followerUserId,true);
        $criteria->compare('followedUserId',$this->followedUserId,true);
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
     * @return Follower the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get followers full detail from user table
     */
    public static function getQueryForFollower($selectedUserId, $userId=0)
    {
        $sql = "SELECT u.id";
        $sql .= ",u.role";
        $sql .= ",IFNULL(u.userName, '') as 'userName'";
        $sql .= ",IFNULL(u.email, '') as 'email'";
        $sql .= ",IFNULL(u.fullName, '') as 'fullName'";
        $sql .= ",IFNULL(u.gender, '') as 'gender'";
        $sql .= ",IFNULL(u.age, '') as 'age'";
        $sql .= ",IFNULL(u.country, '') as 'country'";
        $sql .= ",IFNULL(u.state, '') as 'state'";
        $sql .= ",IFNULL(u.city, '') as 'city'";
        $sql .= ",IFNULL(u.address, '') as 'address'";
        $sql .= ",IFNULL(u.postcode, '') as 'postcode'";
        $sql .= ",IFNULL(u.latitude, 0) as 'latitude'";
        $sql .= ",IFNULL(u.longitude, 0) as 'longitude'";
        $sql .= ",IFNULL(u.phone, '') as 'phone'";
        $sql .= ",IFNULL(u.aboutMe, '') as 'aboutMe'";
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as image';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as thumb';
        $sql .= ",IFNULL(u.facebookId, '') as 'facebookId'";
        $sql .= ",IFNULL(u.twitterId, '') as 'twitterId'";
        $sql .= ",IFNULL(u.googleId, '') as 'googleId'";
        $sql .= ",IFNULL(u.linkedinId, '') as 'linkedinId'";
        $sql .= ",IFNULL(u.facebookLink, '') as 'facebookLink'";
        $sql .= ",IFNULL(u.twitterLink, '') as 'twitterLink'";
        $sql .= ",IFNULL(u.googleLink, '') as 'googleLink'";
        $sql .= ",IFNULL(u.linkedinLink, '') as 'linkedinLink'";
        $sql .= ",IFNULL(u.webAddress, '') as 'webAddress'";
        $sql .= ",IFNULL(s.deviceToken, '') as 'deviceToken'";
        $sql .= ",IFNULL(s.deviceBadge, 0) as 'deviceBadge'";
        
        if($userId)
            $sql .= ",(SELECT COUNT(*) FROM `follower` f WHERE f.followedUserId=u.id AND f.followerUserId=$userId) as 'iFollowIt'";
        else
            $sql .= ",0 as 'iFollowIt'";
        
        $sql .= " FROM user u JOIN follower f ON u.id = f.followerUserId";
        $sql .= " LEFT JOIN session s ON s.userId = u.id";
        $sql .= " WHERE f.followedUserId = $selectedUserId";
        
        return $sql;
    }
    
    /**
     * Returns query to get followed full detail from user table
     */
    public static function getQueryForFollowed($selectedUserId, $name = '')
    {
        $sql = "SELECT u.id";
        $sql .= ",u.role";
        $sql .= ",IFNULL(u.userName, '') as 'userName'";
        $sql .= ",IFNULL(u.email, '') as 'email'";
        $sql .= ",IFNULL(u.fullName, '') as 'fullName'";
        $sql .= ",IFNULL(u.gender, '') as 'gender'";
        $sql .= ",IFNULL(u.age, '') as 'age'";
        $sql .= ",IFNULL(u.country, '') as 'country'";
        $sql .= ",IFNULL(u.state, '') as 'state'";
        $sql .= ",IFNULL(u.city, '') as 'city'";
        $sql .= ",IFNULL(u.address, '') as 'address'";
        $sql .= ",IFNULL(u.postcode, '') as 'postcode'";
        $sql .= ",IFNULL(u.latitude, 0) as 'latitude'";
        $sql .= ",IFNULL(u.longitude, 0) as 'longitude'";
        $sql .= ",IFNULL(u.phone, '') as 'phone'";
        $sql .= ",IFNULL(u.aboutMe, '') as 'aboutMe'";
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as image';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as thumb';
        $sql .= ",IFNULL(u.facebookId, '') as 'facebookId'";
        $sql .= ",IFNULL(u.twitterId, '') as 'twitterId'";
        $sql .= ",IFNULL(u.googleId, '') as 'googleId'";
        $sql .= ",IFNULL(u.linkedinId, '') as 'linkedinId'";
        $sql .= ",IFNULL(u.facebookLink, '') as 'facebookLink'";
        $sql .= ",IFNULL(u.twitterLink, '') as 'twitterLink'";
        $sql .= ",IFNULL(u.googleLink, '') as 'googleLink'";
        $sql .= ",IFNULL(u.linkedinLink, '') as 'linkedinLink'";
        $sql .= ",IFNULL(u.webAddress, '') as 'webAddress'";
        $sql .= ",IFNULL(s.deviceToken, '') as 'deviceToken'";
        $sql .= ",IFNULL(s.deviceBadge, 0) as 'deviceBadge'";
        $sql .= " FROM user u JOIN follower f ON u.id = f.followedUserId";
        $sql .= " LEFT JOIN session s ON s.userId = u.id";
        $sql .= " WHERE f.followerUserId = $selectedUserId";
        
        
        $name = trim($name);
        if(strlen($name) > 0){
        	$sql .= " AND (u.userName LIKE '%$name%'  or u.fullName LIKE '%$name%' )";
        }
        
        return $sql;
    }
    
    public static function autoFollow($userId)
    {
    	
    	
    	$sql = "INSERT INTO `follower`(`followerUserId`, `followedUserId`) SELECT id, '$userId' from `user` WHERE id != $userId and id > 0 ";
    	Yii::app()->db->createCommand($sql)->query();
    	
 		$sql = "INSERT INTO `follower`(`followerUserId`, `followedUserId`) SELECT '$userId', id from `user` WHERE id != $userId and id > 0 ";
 		Yii::app()->db->createCommand($sql)->query();
    	
        //get all users
//         $users= User::getUserNames();
//         foreach($users as $user)
//         {
//             if($userId != $user['id'])
//             {
//                 //current user follows other user (if not already followed)
//                 $follower1 = new Follower('follow_api');
//                 $follower1->followerUserId = $userId;
//                 $follower1->followedUserId = $user['id'];
//                 $follower1->save();
                
//                 //other user follows current user  (if not already followed)
//                 $follower2 = new Follower('follow_api');
//                 $follower2->followerUserId = $user['id'];
//                 $follower2->followedUserId = $userId;
//                 $follower2->save();
//             }
//         }
    }
}
