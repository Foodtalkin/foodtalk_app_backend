<?php

/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property string $id
 * @property string $userId
 * @property string $checkedInRestaurantId
 * @property string $image
 * @property string $tip
 * @property integer $sendPushNotification
 * @property integer $shareOnFacebook
 * @property integer $shareOnTwitter
 * @property integer $shareOnInstagram
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Like[] $likes
 * @property User $user
 * @property Restaurant $checkedInRestaurant
 * @property TagMap[] $tagMaps
 */
class Post extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'post';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId', 'required'),
            array('sendPushNotification, shareOnFacebook, shareOnTwitter, shareOnInstagram, isDisabled', 'numerical', 'integerOnly'=>true),
            array('userId, checkedInRestaurantId, createId, updateId', 'length', 'max'=>10),
            array('image, disableReason', 'length', 'max'=>128),
            array('tip', 'length', 'max'=>500),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, checkedInRestaurantId, image, tip, sendPushNotification, shareOnFacebook, shareOnTwitter, shareOnInstagram, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'comments' => array(self::HAS_MANY, 'Comment', 'postId'),
            'likes' => array(self::HAS_MANY, 'Like', 'postId'),
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'checkedInRestaurant' => array(self::BELONGS_TO, 'Restaurant', 'checkedInRestaurantId'),
            'tagMaps' => array(self::HAS_MANY, 'TagMap', 'postId'),
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
            'checkedInRestaurantId' => 'Checked In Restaurant',
            'image' => 'Image',
            'tip' => 'Tip',
            'sendPushNotification' => 'Send Push Notification',
            'shareOnFacebook' => 'Share On Facebook',
            'shareOnTwitter' => 'Share On Twitter',
            'shareOnInstagram' => 'Share On Instagram',
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
        $criteria->compare('checkedInRestaurantId',$this->checkedInRestaurantId,true);
        $criteria->compare('image',$this->image,true);
        $criteria->compare('tip',$this->tip,true);
        $criteria->compare('sendPushNotification',$this->sendPushNotification);
        $criteria->compare('shareOnFacebook',$this->shareOnFacebook);
        $criteria->compare('shareOnTwitter',$this->shareOnTwitter);
        $criteria->compare('shareOnInstagram',$this->shareOnInstagram);
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
     * @return Post the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get post full detail
     */
    public static function getQuery($userId, $postUserId=0, $includeFollowed=false, $includeCount=false, $latitude=0, $longitude=0)
    {
        $sql = 'SELECT p.id';
        $sql .= ',p.userId';
        $sql .= ',IFNULL(p.checkedInRestaurantId, 0) as checkedInRestaurantId';
//         $sql .= ',IFNULL(CONCAT("' . imagePath('post') . '", p.image), "") as postImage';
//         $sql .= ',IFNULL(CONCAT("' . thumbPath('post') . '", p.image), "") as postThumb';
        
//         error_log(thumbPath('post', true));
        $sql .= ',IFNULL(p.cloud_url, "") as postImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('post', true) . '", SUBSTRING_INDEX(p.cloud_url , \'upload\', -1)), "") as postThumb';
//         $sql .= ',IFNULL(SUBSTRING_INDEX(p.cloud_url , \'upload\', -1), "") as postThumb';
//         cloud_url
        $sql .= ',IFNULL(p.tip, "") as tip';
        $sql .= ',p.createDate';
        $sql .= ',NOW() as currentDate';
        $sql .= ',IFNULL(u.userName, "") as userName';
        $sql .= ',IFNULL(u.email, "") as email';
        $sql .= ',IFNULL(u.country, "") as country';
        $sql .= ',IFNULL(u.state, "") as state';
        $sql .= ',IFNULL(u.city, "") as city';
        $sql .= ',IFNULL(u.address, "") as address';
        $sql .= ',IFNULL(u.postcode, "") as postcode';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as userImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as userThumb';
        $sql .= ',IFNULL(u.facebookId, "") as facebookId';
        $sql .= ',IFNULL(r.restaurantName, "") as restaurantName';
        
        $sql .= ',IFNULL(r.area, "") as area';
        $sql .= ',IFNULL(r.region, "") as region';
        
        //$sql .= ",RADIANS(ACOS(SIN(DEGREES($latitude)) * SIN(DEGREES(r.latitude)) + COS(DEGREES($latitude)) * COS(DEGREES(r.latitude)) * COS(DEGREES($longitude - r.longitude)))) * 111189.577 as restaurantDistance";
        $sql .= ",DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006 as restaurantDistance";
        
        if($includeCount)
        {
            $sql .= ',(SELECT COUNT(*) FROM `like` l WHERE l.postId=p.id AND l.isDisabled=0) as likeCount';
            $sql .= ',(SELECT COUNT(*) FROM comment c WHERE c.postId=p.id AND c.isDisabled=0) as commentCount';
            $sql .= ',(SELECT COUNT(*) FROM `flag` f WHERE f.postId=p.id AND f.isDisabled=0) as flagCount';
            $sql .= ',(SELECT COUNT(*) FROM `like` l2 WHERE l2.postId=p.id AND l2.isDisabled=0 AND l2.userId='.$userId.') as iLikedIt';
            $sql .= ',(SELECT COUNT(*) FROM `flag` f2 WHERE f2.postId=p.id AND f2.isDisabled=0 AND f2.userId='.$userId.') as iFlaggedIt';
        }
        
        $sql .= ' FROM post p JOIN user u ON p.userId = u.id';
        $sql .= ' LEFT JOIN restaurant r ON p.checkedInRestaurantId = r.id';
        $sql .= ' WHERE p.isDisabled = 0';
        $sql .= ' AND u.isDisabled = 0';
        $sql .= ' AND (r.isDisabled is null OR r.isDisabled = 0)';
        
        if($includeFollowed)
        {
            $sql .= " AND (p.userId = $postUserId";
            $sql .= "  OR p.userId IN (SELECT f.followedUserId FROM follower f WHERE f.followerUserId=$postUserId AND (f.isDisabled is null OR f.isDisabled = 0)))";
        }
        else if($postUserId != 0)
        {
            $sql .= " AND p.userId = $postUserId";
        }
        
        return $sql;
    }
    
    public static function getPostsByUserId($userId, $postUserId, $includeFollowed=false, $includeCount=false, $tagId=0, $recordCount=0, $exceptions='')
    {
        //fetch all tips related to current restaurant
        $sql = self::getQuery($userId, $postUserId, $includeFollowed, $includeCount);
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        if($tagId)
        {
            $tagPostSql = 'SELECT GROUP_CONCAT(tm.postId) as tagPostIds FROM tagMap tm WHERE tm.tagId=' .$tagId;
            $tagPostIds  = Yii::app()->db->createCommand($tagPostSql)->queryRow(true);
            
            if(!is_null($tagPostIds) && !empty($tagPostIds))
                $sql .= ' AND p.id IN (' .$tagPostIds['tagPostIds']. ')';
        }
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $posts;
    }
    
    public static function getTipPostsByUserId($userId, $postUserId, $recordCount=0, $exceptions='')
    {
        //fetch all tip posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true);
        $sql .= ' AND p.tip !=""';
        $sql .= ' AND p.tip IS NOT NULL';
        $sql .= ' AND (p.image ="" OR p.image IS NULL)';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
    public static function getImagePostsByUserId($userId, $postUserId, $recordCount=0, $exceptions='')
    {
        //fetch all image posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true);
        $sql .= ' AND p.image !=""';
        $sql .= ' AND p.image IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
    public static function getCheckInPostsByUserId($userId, $postUserId, $recordCount=0, $exceptions='')
    {
        //fetch all checkin posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true);
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
    public static function getCheckInPostsByUserId2($userId, $postUserId, $recordCount=0, $exceptions='')
    {
        //fetch all checkin posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true);
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY u.id ASC, p.createDate DESC';
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
        $filteredPosts = array();
        $uId = null;
        $pDate = null;
        $rc = 0;
        foreach ($posts as &$post)
        {
            $newPostDate = DateTime::createFromFormat("Y-m-d H:i:s", $post['createDate']);
            if($uId != $post['userId'] && $pDate != $newPostDate)
            {
                $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
                $filteredPosts[] = $post;
                $rc++;
                
                if($recordCount != 0 && $rc >= $recordCount)
                    break;
            }
            $uId = $post['userId'];
            $pDate = $newPostDate;
        }
        
        return $filteredPosts;
    }
    
    public static function getUniqueCheckInPostsByUserId($userId, $postUserId, $recordCount=0, $exceptions='')
    {
        //fetch all checkin posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true);
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY u.id ASC, p.checkedInRestaurantId ASC, p.createDate DESC';
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
        $filteredPosts = array();
        $uId = null;    //userId
        $rId = null;    //restaurantId
        $rc = 0;
        
        foreach ($posts as &$post)
        {
            if($uId != $post['userId'] || $rId != $post['checkedInRestaurantId'])
            {
                $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
                $filteredPosts[] = $post;
            }
            
            if(!empty($filteredPosts))
            {
                $lastFilteredPost = &$filteredPosts[count($filteredPosts) - 1];
                
                if(isset($lastFilteredPost['restaurantCheckinCount']))
                    $lastFilteredPost['restaurantCheckinCount'] = $lastFilteredPost['restaurantCheckinCount'] + 1;
                else
                    $lastFilteredPost['restaurantCheckinCount'] = 1;
            }
            
            $uId = $post['userId'];
            $rId = $post['checkedInRestaurantId'];
            
            $rc++;
                
            if($recordCount != 0 && $rc >= $recordCount)
                break;
        }
        
        return $filteredPosts;
    }
    
    public static function getTipPostsByRestaurantId($userId, $restaurantId, $recordCount=0, $exceptions='')
    {
        //fetch all tip posts related to a restaurant
        $sql = self::getQuery($userId, 0, false, true);
        $sql .= ' AND p.tip !=""';
        $sql .= ' AND p.tip IS NOT NULL';
        $sql .= ' AND (p.image IS NULL OR p.image = "")';
        $sql .= ' AND p.checkedInRestaurantId=' . $restaurantId;
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
/*	$sql2 = '(SELECT COUNT(*) FROM `follower` f WHERE f.followedUserId=' . $userId;
        $sql2 .= ') as followersCount';
        $no_of_followers = Yii::app()->db->createCommand($sql2)->queryAll(true);*/
        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        //	$post['followersCount'] = $no_of_followers; 
	 }
        
        return $posts;
    }
    
    public static function getImagePostsByRestaurantId($userId, $restaurantId, $recordCount=0, $exceptions='')
    {
        //fetch all tip posts related to a restaurant
        $sql = self::getQuery($userId, 0, false, true);
        $sql .= ' AND p.image !=""';
        $sql .= ' AND p.image IS NOT NULL';
        $sql .= ' AND p.checkedInRestaurantId=' . $restaurantId;
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
    public static function getImageCheckInPosts($userId, $latitude=0, $longitude=0, $tagId=0, $recordCount=0, $exceptions='')
    {
        //fetch all tip posts related to a restaurant
        $sql = self::getQuery($userId, 0, false, true, $latitude, $longitude);
        $sql .= ' AND p.image !=""';
        $sql .= ' AND p.image IS NOT NULL';
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        
//        if(!empty($hashtag))
//            $sql .= ' AND p.tip LIKE "%#' .$hashtag. '%"';
        
        if($tagId)
            $sql .= ' AND p.id IN (SELECT tm.postId FROM tagMap tm WHERE tm.tagId='.$tagId.')';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY restaurantDistance ASC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
    public static function getCheckInPosts($userId, $latitude=0, $longitude=0, $tagId=0, $recordCount=0, $exceptions='')
    {
        //fetch all tip posts related to a restaurant
        $sql = self::getQuery($userId, 0, false, true, $latitude, $longitude);
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        $sql .= ' AND (p.image IS NULL OR p.image="")';
        
//        if(!empty($hashtag))
//            $sql .= ' AND p.tip LIKE "%#' .$hashtag. '%"';
        
        if($tagId)
            $sql .= ' AND p.id IN (SELECT tm.postId FROM tagMap tm WHERE tm.tagId='.$tagId.')';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY restaurantDistance ASC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
    /**
     * Returns checkin images only
     */
    public static function getCheckinImages($postUserId, $recordCount=0, $exceptions='')
    {
        $sql = 'SELECT p.id';
        $sql .= ',IFNULL(CONCAT("' . imagePath('post') . '", p.image), "") as postImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('post') . '", p.image), "") as postThumb';
        $sql .= ' FROM post p';
        $sql .= ' WHERE p.isDisabled = 0';
        $sql .= ' AND p.userId =' . $postUserId;
        $sql .= ' AND p.image !=""';
        $sql .= ' AND p.image IS NOT NULL';
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $posts;
    }
}
