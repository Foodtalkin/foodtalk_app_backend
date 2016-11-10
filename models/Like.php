<?php

/**
 * This is the model class for table "like".
 *
 * The followings are the available columns in table 'like':
 * @property string $id
 * @property string $userId
 * @property string $postId
 * @property string $postUserId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Post $post
 * @property User $postUser
 */
class Like extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'like';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, postId, postUserId', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('userId, postId, postUserId, createId, updateId', 'length', 'max'=>10),
            array('disableReason', 'length', 'max'=>128),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, postId, postUserId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'post' => array(self::BELONGS_TO, 'Post', 'postId'),
            'postUser' => array(self::BELONGS_TO, 'User', 'postUserId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'User',
            'postId' => 'Post',
            'postUserId' => 'Post User',
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
        $criteria->compare('userId',$this->userId,true);
        $criteria->compare('postId',$this->postId,true);
        $criteria->compare('postUserId',$this->postUserId,true);
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
     * @return Like the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get all likes of a post and related users
     */
    public static function getQuery($imagePath, $thumbPath, $postId, $userId)
    {
        $sql = "SELECT u.id";
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
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image, "?type=large"), "") as image';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image, "?type=small"), "") as thumb';
//         $sql .= ",IFNULL(CONCAT('$imagePath', u.image), '') as 'image'";
//         $sql .= ",IFNULL(CONCAT('$thumbPath', u.image), '') as 'thumb'";
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
        $sql .= ",l.createDate";
        $sql .= ",(SELECT COUNT(*) FROM `follower` f WHERE f.followedUserId=u.id AND f.followerUserId=$userId) as 'iFollowIt'";
        $sql .= " FROM user u JOIN `like` l ON u.id = l.userId";
        $sql .= " LEFT JOIN session s ON s.userId = u.id";
        $sql .= " WHERE l.postId = $postId and l.isDisabled ";
        
        return $sql;
    }
}
