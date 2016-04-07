<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $role
 * @property string $userName
 * @property string $email
 * @property string $password
 * @property string $fullName
 * @property string $gender
 * @property integer $age
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $address
 * @property string $postcode
 * @property double $latitude
 * @property double $longitude
 * @property string $phone
 * @property string $aboutMe
 * @property string $image
 * @property string $facebookId
 * @property string $twitterId
 * @property string $googleId
 * @property string $linkedinId
 * @property string $facebookLink
 * @property string $twitterLink
 * @property string $googleLink
 * @property string $linkedinLink
 * @property string $webAddress
 * @property integer $sendPushNotification
 * @property integer $shareOnFacebook
 * @property integer $shareOnTwitter
 * @property integer $shareOnInstagram
 * @property string $activationCode
 * @property integer $isActivated
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Comment[] $comments1
 * @property Favourite[] $favourites
 * @property Follower[] $followers
 * @property Follower[] $followers1
 * @property Like[] $likes
 * @property Like[] $likes1
 * @property Post[] $posts
 * @property Session[] $sessions
 * @property Subscription[] $subscriptions
 * @property UserCuisine[] $userCuisines
 */
class User extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('age, sendPushNotification, shareOnFacebook, shareOnTwitter, shareOnInstagram, isActivated, isDisabled', 'numerical', 'integerOnly'=>true),
            array('latitude, longitude', 'numerical'),
            array('role', 'length', 'max'=>16),
            array('userName, email, fullName, address, image, disableReason', 'length', 'max'=>128),
            array('password', 'length', 'max'=>40),
            array('gender, activationCode, createId, updateId', 'length', 'max'=>10),
            array('country, state, city', 'length', 'max'=>50),
            array('postcode, phone', 'length', 'max'=>20),
            array('aboutMe', 'length', 'max'=>500),
            array('facebookId, facebookLink', 'length', 'max'=>32),
            array('twitterId, googleId, linkedinId, twitterLink, googleLink, linkedinLink', 'length', 'max'=>255),
            array('webAddress', 'length', 'max'=>250),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, role, userName, email, password, fullName, gender, age, country, state, city, address, postcode, latitude, longitude, phone, aboutMe, image, facebookId, twitterId, googleId, linkedinId, facebookLink, twitterLink, googleLink, linkedinLink, webAddress, sendPushNotification, shareOnFacebook, shareOnTwitter, shareOnInstagram, activationCode, isActivated, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'comments' => array(self::HAS_MANY, 'Comment', 'userId'),
            'comments1' => array(self::HAS_MANY, 'Comment', 'postUserId'),
            'favourites' => array(self::HAS_MANY, 'Favourite', 'userId'),
            'followers' => array(self::HAS_MANY, 'Follower', 'followerUserId'),
            'followers1' => array(self::HAS_MANY, 'Follower', 'followedUserId'),
            'likes' => array(self::HAS_MANY, 'Like', 'userId'),
            'likes1' => array(self::HAS_MANY, 'Like', 'postUserId'),
            'posts' => array(self::HAS_MANY, 'Post', 'userId'),
            'sessions' => array(self::HAS_MANY, 'Session', 'userId'),
            'subscriptions' => array(self::HAS_MANY, 'Subscription', 'userId'),
            'userCuisines' => array(self::HAS_MANY, 'UserCuisine', 'userId'),
        	'reportedCount' => array(self::STAT, 'Flag', 'postUserId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'role' => 'Role',
            'userName' => 'User Name',
            'email' => 'Email',
            'password' => 'Password',
            'fullName' => 'Full Name',
            'gender' => 'Gender',
            'age' => 'Age',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'phone' => 'Phone',
            'aboutMe' => 'About Me',
            'image' => 'Image',
            'facebookId' => 'Facebook',
            'twitterId' => 'Twitter',
            'googleId' => 'Google',
            'linkedinId' => 'Linkedin',
            'facebookLink' => 'Facebook Link',
            'twitterLink' => 'Twitter Link',
            'googleLink' => 'Google Link',
            'linkedinLink' => 'Linkedin Link',
            'webAddress' => 'Web Address',
            'sendPushNotification' => 'Send Push Notification',
            'shareOnFacebook' => 'Share On Facebook',
            'shareOnTwitter' => 'Share On Twitter',
            'shareOnInstagram' => 'Share On Instagram',
            'activationCode' => 'Activation Code',
            'isActivated' => 'Is Activated',
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
        $criteria->compare('role',$this->role,true);
        $criteria->compare('userName',$this->userName,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('fullName',$this->fullName,true);
        $criteria->compare('gender',$this->gender,true);
        $criteria->compare('age',$this->age);
        $criteria->compare('country',$this->country,true);
        $criteria->compare('state',$this->state,true);
        $criteria->compare('city',$this->city,true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('postcode',$this->postcode,true);
        $criteria->compare('latitude',$this->latitude);
        $criteria->compare('longitude',$this->longitude);
        $criteria->compare('phone',$this->phone,true);
        $criteria->compare('aboutMe',$this->aboutMe,true);
        $criteria->compare('image',$this->image,true);
        $criteria->compare('facebookId',$this->facebookId,true);
        $criteria->compare('twitterId',$this->twitterId,true);
        $criteria->compare('googleId',$this->googleId,true);
        $criteria->compare('linkedinId',$this->linkedinId,true);
        $criteria->compare('facebookLink',$this->facebookLink,true);
        $criteria->compare('twitterLink',$this->twitterLink,true);
        $criteria->compare('googleLink',$this->googleLink,true);
        $criteria->compare('linkedinLink',$this->linkedinLink,true);
        $criteria->compare('webAddress',$this->webAddress,true);
        $criteria->compare('sendPushNotification',$this->sendPushNotification);
        $criteria->compare('shareOnFacebook',$this->shareOnFacebook);
        $criteria->compare('shareOnTwitter',$this->shareOnTwitter);
        $criteria->compare('shareOnInstagram',$this->shareOnInstagram);
        $criteria->compare('activationCode',$this->activationCode,true);
        $criteria->compare('isActivated',$this->isActivated);
        $criteria->compare('isDisabled',$this->isDisabled);
        $criteria->compare('disableReason',$this->disableReason,true);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        if(Yii::app()->controller->action->id=='disabled')
        	$criteria->addCondition("t.isDisabled = 1");
        else
        	$criteria->addCondition("t.isDisabled = 0");
        
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        		'sort'=>array(
        				'defaultOrder'=>'t.createDate desc'
        		),
        		'pagination'=>array(
        				'pageSize'=>100
        		),
        		
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get user records
     */
    public static function getQuery($userId, $selectAllFields=false, $includeCuisine=false, $includeCount=false)
    {
        $sql = 'SELECT u.id';
        $sql .= ',IFNULL(u.userName, "") as userName';
        $sql .= ',IF(u.role="manager" ,"Tester,FoodTalk", "FoodTalk") as channels';
        $sql .= ',IFNULL(u.fullName, "") as fullName';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image, "?type=large"), "") as image';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image, "?type=small"), "") as thumb';
        $sql .= ',IFNULL(u.facebookId, "") as facebookId';
        
        if($selectAllFields)
        {
            $sql .= ',IFNULL(u.email, "") as email';
            $sql .= ',IFNULL(u.gender, "") as gender';
            $sql .= ',IFNULL(u.age, "") as age';
            $sql .= ',IFNULL(u.country, "") as country';
            $sql .= ',IFNULL(u.state, "") as state';
            $sql .= ',IFNULL(u.city, "") as city';
            $sql .= ',IFNULL(u.address, "") as address';
            $sql .= ',IFNULL(u.postcode, "") as postcode';
            $sql .= ',IFNULL(u.latitude, 0) as latitude';
            $sql .= ',IFNULL(u.longitude, 0) as longitude';
            $sql .= ',IFNULL(u.phone, "") as phone';
            $sql .= ',IFNULL(u.aboutMe, "") as aboutMe';
            $sql .= ',IFNULL(u.twitterId, "") as twitterId';
            $sql .= ',IFNULL(u.googleId, "") as googleId';
            $sql .= ',IFNULL(u.linkedinId, "") as linkedinId';
            $sql .= ',IFNULL(u.webAddress, "") as webAddress';
            $sql .= ',u.sendPushNotification';
            $sql .= ',u.shareOnFacebook';
            $sql .= ',u.shareOnTwitter';
            $sql .= ',u.shareOnInstagram';
        }
        
        if($includeCuisine)
        {
            $sql .= ',(SELECT GROUP_CONCAT(c.id) FROM cuisine c JOIN userCuisine uc on c.id = uc.cuisineId WHERE uc.userId=u.id) as cuisineIds';
            $sql .= ',(SELECT GROUP_CONCAT(c.cuisineName) FROM cuisine c JOIN userCuisine uc on c.id = uc.cuisineId WHERE uc.userId=u.id) as cuisineNames';
        }
        
        if($includeCount)
        {
            $sql .= ',(SELECT COUNT(*) FROM `post` p WHERE p.userId=u.id AND p.checkedInRestaurantId IS NOT NULL AND p.isDisabled=0) as checkInCount';
            $sql .= ',(SELECT COUNT(*) FROM `post` p WHERE p.userId=u.id AND p.tip !="" AND p.tip IS NOT NULL AND p.isDisabled=0) as tipCount';
            $sql .= ',(SELECT COUNT(*) FROM `post` p WHERE p.userId=u.id AND p.image !="" AND p.image IS NOT NULL AND p.isDisabled=0) as imageCount';
            $sql .= ',(SELECT COUNT(*) FROM `post` p WHERE p.userId=u.id AND p.checkedInRestaurantId IS NOT NULL AND p.image !="" AND p.image IS NOT NULL AND p.isDisabled=0) as checkInImageCount';
            $sql .= ',(SELECT COUNT(*) FROM `follower` f WHERE f.followedUserId=u.id) as followersCount';
            $sql .= ',(SELECT COUNT(*) FROM `follower` f WHERE f.followerUserId=u.id) as followingCount';
            $sql .= ',(SELECT COUNT(*) FROM `follower` f2 WHERE f2.followedUserId=u.id AND f2.followerUserId='.$userId.') as iFollowedIt';
        }
        
        $sql .= ' FROM user u';
        
        return $sql;
    }
    
    /**
     * Returns user profile and related data
     */
    public static function getProfileById($userId, $profileUserId)
    {
        $sql = self::getQuery($userId, false, false, true);
        $sql .= ' WHERE u.id=' .$profileUserId;
        //LogInFile($sql);
        $profile = Yii::app()->db->createCommand($sql)->queryRow(true);
        return $profile;
    }
    
    /**
     * Returns user records by facebookIds
     */
    public static function getUsersByFacebookIds($userId, $facebookIds)
    {
        $sql = self::getQuery($userId, false, false, true);
        $sql .= ' WHERE u.facebookId in (' . $facebookIds . ')';
        $users = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $users;
    }
    
    /**
     * 
     * @param type $userId
     * @param type $recordCount
     * @param type $exceptions
     * @return type
     */
    public static function getSuggestedFollows($userId, $searchText='', $recordCount=15, $exceptions='')
    {
        //fetch all users that can be followed
        $sql = self::getQuery($userId, false, false, true);
        $sql .= ' WHERE u.isDisabled=0';
        $sql .= ' AND u.id != ' .$userId;
        $sql .= ' AND u.id NOT IN (SELECT f.followedUserId FROM follower f WHERE f.followerUserId=' .$userId. ')';
        
        if($searchText)
            $sql .= " AND (u.userName LIKE '%$searchText%' OR u.fullName LIKE '%$searchText%')";
        
        if($exceptions)
            $sql .= ' AND u.id NOT IN (' .$exceptions. ')';
        
        
        $sql .= ' HAVING checkInImageCount >= 3';
        $sql .= ' ORDER BY checkInCount DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $users = Yii::app()->db->createCommand($sql)->queryAll(true);
        
        foreach($users as &$user)
        {
            $user['recentCheckins'] = Post::getCheckinImages($user['id'], 3);
        }
        
        return $users;
    }
    
    public static function getUserNames($searchText='')
    {
        $sql = 'SELECT u.id';
        $sql .= ',IFNULL(u.userName, "") as userName';
        $sql .= ',IFNULL(u.fullName, "") as fullName';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as image';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as thumb';
        $sql .= ' FROM user u';
        $sql .= ' WHERE u.isDisabled = 0 ';
//         (4,5,11,12,210)
        if(!empty($searchText))
            $sql .= " AND (u.userName LIKE '%$searchText%' OR u.fullName LIKE '%$searchText%')";
        else 
        	$sql .= " AND u.id != 1428 ";
        $sql .= ' ORDER BY u.fullName';
        $users = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $users;
    }
}
