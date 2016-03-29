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
        	'dishReview' => array(self::HAS_ONE, 'DishReview', 'postId'),        		
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'checkedInRestaurant' => array(self::BELONGS_TO, 'Restaurant', 'checkedInRestaurantId'),
            'tagMaps' => array(self::HAS_MANY, 'TagMap', 'postId'),
        	'postReport' => array(self::HAS_MANY, 'Flag', 'postId'),
        	'reportedCount' => array(self::STAT, 'Flag', 'postId'),        		
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
    public function search($type = false, $admin = false, $options = array())
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('t.id',$this->id,true);
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
        

        
        $criteria->with[]='dishReview';
//         $criteria->compare("DishReview.",$this->userId);

        $criteria->with[]='user';
        $criteria->compare("user.userName",$this->userId);
        

        
        
//         $criteria->with[]='checkedInRestaurant';
//         $criteria->compare("checkedInRestaurant.restaurantName",$this->checkedInRestaurantId);

        
 		if(Yii::app()->controller->action->id=='disabled')
 			$criteria->addCondition("t.isDisabled = 1");
 		else
	        $criteria->addCondition("t.isDisabled = 0");
        
		$order = 't.createDate desc';
		
		if($admin){
		
// 			if($type=='restaurant'){
				
				
				if(!empty($options)){
					if(isset($options['restaurant']) && is_numeric($options['restaurant']))
						$criteria->addCondition("checkedInRestaurantId = ".$options['restaurant']);
					if(isset($options['user']) && is_numeric($options['user']))
						$criteria->addCondition("t.userId = ".$options['user']);
					
					if(isset($options['dish']) && is_numeric($options['dish'])){
						$criteria->join .=" INNER JOIN dishReview dr ON dr.postId = t.id ";
						$criteria->join .=" INNER JOIN dish d ON dishId = d.id AND d.id=".$options['dish'];						
					}
					
				}
				// 		        $criteria->addCondition("image IS NOT NULL");
// 			}
			
				
				
			if($type=='reviews'){
				$criteria->addCondition("t.image IS NULL");
				$criteria->addCondition("checkedInRestaurantId IS not NULL");
				// 		        $criteria->addCondition("image IS NOT NULL");
			}	

			if($type=='inactive'){
				
				$criteria->with[]='checkedInRestaurant';
				$criteria->addCondition("checkedInRestaurantId IS not NULL");				
				$criteria->addCondition("checkedInRestaurant.isActivated = 0");
				$criteria->addCondition("checkedInRestaurant.isDisabled = 0");

			}
			
			
	        if($type=='checkin'){   	
		        $criteria->addCondition("checkedInRestaurantId IS not NULL");
		        $criteria->addCondition("t.image IS NOT NULL");
	        }
	        
	        if($type=='post'){
	        	$criteria->addCondition("checkedInRestaurantId IS NULL");
	        	$criteria->addCondition("t.image IS NULL");
	        }
	        
	        if($type=='images'){
	        	$criteria->addCondition("checkedInRestaurantId IS NULL");
	        	$criteria->addCondition("t.image IS NOT NULL");
	        }
	        
	        if($type=='reported'){
	        	$criteria->join .=" Inner JOIN flag f ON f.postId = t.id ";
	        	$order = 'f.createDate desc';
	        }
	        
// 	        if($type=='reviews'){
// 	        	$criteria->addCondition("checkedInRestaurantId IS NOT NULL");
// 	        	$criteria->addCondition("image IS NULL");
// 	        }

		}
// 			$criteria->order = $order;

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        		'sort'=>array(
        		'defaultOrder'=>$order
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
        $sql .= ',p.userId, p.checkinId';
        $sql .= ',IFNULL(p.checkedInRestaurantId, 0) as checkedInRestaurantId';
        $sql .= ',IFNULL(CONCAT("' . imagePath('post') . '", p.image), "") as postImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('post') . '", p.image), "") as postThumb';
        $sql .= ',IFNULL(p.tip, "") as tip';
        $sql .= ',IFNULL(d.dishName, "") as dishName';        
        $sql .= ',IFNULL(dr.rating, "0") as rating';
        
        $sql .= ',p.createDate';
        $sql .= ',NOW() as currentDate';
        $sql .= ',IFNULL(u.userName, "") as userName';
        $sql .= ',IFNULL(u.email, "") as email';
        $sql .= ',IFNULL(u.country, "") as country';
        $sql .= ',IFNULL(u.state, "") as state';
        $sql .= ',IFNULL(u.city, "") as city';
        $sql .= ',IFNULL(u.address, "") as address';
        $sql .= ',IFNULL(u.postcode, "") as postcode';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image, "?type=large"), "") as userImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image, "?type=small"), "") as userThumb';
        $sql .= ',IFNULL(u.facebookId, "") as facebookId';
        $sql .= ',IFNULL(r.restaurantName, "") as restaurantName';
        
        $sql .= ' , IFNULL(r.isActivated, 0) as restaurantIsActive ';
        
        if($latitude && $longitude)
        
        //$sql .= ",RADIANS(ACOS(SIN(DEGREES($latitude)) * SIN(DEGREES(r.latitude)) + COS(DEGREES($latitude)) * COS(DEGREES(r.latitude)) * COS(DEGREES($longitude - r.longitude)))) * 111189.577 as restaurantDistance";
        $sql .= ",DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006 as restaurantDistance";
        
        if($includeCount)
        {
        	$sql .= ',(SELECT COUNT(*) FROM `follower` f WHERE f.followedUserId=u.id) as followersCount';       	 
            $sql .= ',(SELECT COUNT(*) FROM `like` l WHERE l.postId=p.id AND l.isDisabled=0) as likeCount';
            $sql .= ',(SELECT COUNT(*) FROM comment c WHERE c.postId=p.id AND c.isDisabled=0) as commentCount';
            $sql .= ',(SELECT COUNT(*) FROM `flag` f WHERE f.postId=p.id AND f.isDisabled=0) as flagCount';
            
            $sql .= ',(SELECT COUNT(*) FROM `bookmark` b2 WHERE b2.postId=p.id AND b2.isDisabled=0) as bookmarkCount';
            
            $sql .= ',(SELECT COUNT(*) FROM `like` l2 WHERE l2.postId=p.id AND l2.isDisabled=0 AND l2.userId='.$userId.') as iLikedIt';
            $sql .= ',(SELECT COUNT(*) FROM `flag` f2 WHERE f2.postId=p.id AND f2.isDisabled=0 AND f2.userId='.$userId.') as iFlaggedIt';
            $sql .= ',(SELECT COUNT(*) FROM `bookmark` b1 WHERE b1.postId=p.id AND b1.isDisabled=0 AND b1.userId='.$userId.') as iBookark';
        }
        
        $sql .= ' FROM post p JOIN user u ON p.userId = u.id';
        
        $sql .= ' LEFT JOIN dishReview dr ON p.id = dr.postId';
        $sql .= ' LEFT JOIN dish d ON d.id = dr.dishId';
        
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

    public static function getPost($postId = false, $userId , $options = array()){
    	
    	$sql = 'SELECT p.`id`, p.`userId`, IFNULL(p.`checkedInRestaurantId`, "") as checkedInRestaurantId  , IFNULL(CONCAT("' . imagePath('post') . '", p.image), "") as postImage , d.dishName, IFNULL(dr.rating, "0") as rating, p.`tip`, u.userName, u.id userId, '
    			
			.'(SELECT COUNT(*) FROM `bookmark` b1 WHERE b1.postId=p.id AND b1.isDisabled=0 AND b1.userId='.$userId.') as iBookark, '
    		.'(SELECT COUNT(*) FROM `bookmark` b2 WHERE b2.postId=p.id AND b2.isDisabled=0) as bookmarkCount, '
    			
    			.'(SELECT COUNT(*) FROM `like` l2 WHERE l2.postId=p.id AND l2.isDisabled=0 AND l2.userId='.$userId.') as iLikedIt, p.createDate, NOW() as currentDate '.
    			', IFNULL(CONCAT("' . imagePath('user') . '", u.image, "?type=large"), "") as userImage '.
    			', IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as userThumb, IFNULL(r.restaurantName, "") as restaurantName'
    			.' , IFNULL(r.isActivated, 0) as restaurantIsActive '    					
			.', (select count(1) from comment where comment.postId = p.id and comment.isDisabled = 0) as comment_count , (select count(1) from `like` where `like`.isDisabled= 0 and `like`.`postId` = p.`id`) as like_count '
			.' FROM `post` p INNER JOIN user u on p.userId = u.id LEFT JOIN restaurant r on p.checkedInRestaurantId = r.id 
					INNER JOIN dishReview dr on p.id = dr.postId INNER JOIN dish d on d.id = dr.dishId and d.isDisabled = 0
					WHERE p.isDisabled = 0 ';
     	if($postId)
    		$sql .= ' and p.id ='.$postId ;
    	if(isset($options['unrated'])  && $options['unrated']){
    		$sql .= 'and  p.userId='.$userId.' and p.image is not null and p.checkedInRestaurantId is not null and dr.rating is null ';	
    	}
    	
    	
//     	error_log($sql);
    	
    	
    	$post = Yii::app()->db->createCommand($sql)->queryAll(true);    	
    	
    	return $post;
    }
    
    
    public static function getPostsByUserId($userId, $postUserId, $includeFollowed=false, $includeCount=false, $tagId=0, $recordCount=9, $exceptions='' , $page=1)
    {
    	$pagestart = ($page-1) * $recordCount;
    	
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
        
//         if($recordCount != 0)
//             $sql .= ' LIMIT ' . $recordCount;
        	$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
        
        
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
    
    public static function getImagePostsByUserId($userId, $postUserId, $recordCount=0, $exceptions='', $page=1, $latitude=0, $longitude=0)
    {
    	
    	$pagestart = ($page-1) * $recordCount;
        //fetch all image posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true, $latitude, $longitude);
        $sql .= ' AND p.image !=""';
        $sql .= ' AND p.image IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
//             $sql .= ' LIMIT ' . $recordCount;
        $sql .= ' LIMIT '. $pagestart .', '. $recordCount;
//         error_log('$sql : =====> '.$sql);
        
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
    
    
    public static function getUniqueCheckInCount($userId, $page=1, $recordCount=10, $exceptions='')
    {
    	
    	$pageStart = ($page-1) * $recordCount;
    	
    	$sql = 'SELECT count(1) checkinCount ,checkedInRestaurantId restaurantId, restaurantName FROM '.
    	'(SELECT DISTINCT checkinId, checkedInRestaurantId, r.restaurantName FROM `post` p INNER JOIN restaurant r on r.id = p.checkedInRestaurantId and r.isDisabled = 0 WHERE p.userId = '.$userId.' and p.isDisabled = 0 and checkinId is not null) a '.
    	' GROUP BY checkedInRestaurantId ORDER BY `checkinCount`  DESC , restaurantName asc limit '.$pageStart. ','.$recordCount;
    	
    	$result = Yii::app()->db->createCommand($sql)->queryAll(true);
    	/*	$sql2 = '(SELECT COUNT(*) FROM `follower` f WHERE f.followedUserId=' . $userId;
    	 $sql2 .= ') as followersCount';
    	$no_of_followers = Yii::app()->db->createCommand($sql2)->queryAll(true);*/
//     	foreach ($posts as &$post)
//     	{
//     		$post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
//     		//	$post['followersCount'] = $no_of_followers;
//     	}
    	
    	return $result;
    	
    }
    
    
    
    public static function getUniqueCheckInPostsByUserId($userId, $postUserId, $recordCount=0, $exceptions='')
    {
        //fetch all checkin posts of given user
        $sql = self::getQuery($userId, $postUserId, false, true);
        $sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        
        $sql .= '  group by p.checkinId';
        
        $sql = 'select a.*, count(1) restaurantCheckinCount from ('.$sql.') as a group by a.checkedInRestaurantId ';
        
        
//         $sql .= ' ORDER BY u.id ASC, p.checkedInRestaurantId ASC, p.createDate DESC';
        
//         error_log($sql);
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
        
        foreach ($posts as &$post){
        	$post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        	$post['restaurantCheckinCount'] = (int) $post['restaurantCheckinCount'];
        	$filteredPosts[] = $post;
        }
        
        
        return $posts;
        
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
    
    public static function getImagePostsByRestaurantId($userId, $restaurantId, $recordCount=0, $exceptions='', $page = 1)
    {
    	
    	$pagestart = ($page-1) * $recordCount;
        //fetch all tip posts related to a restaurant
        $sql = self::getQuery($userId, 0, false, true);
        $sql .= ' AND p.image !=""';
        $sql .= ' AND p.image IS NOT NULL';
        $sql .= ' AND p.checkedInRestaurantId=' . $restaurantId;
        
        if($exceptions)
            $sql .= ' AND p.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY p.createDate DESC';
        
        if($recordCount != 0)
        	$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
//             $sql .= ' LIMIT ' . $recordCount;
        
        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);

        foreach ($posts as &$post)
        {
            $post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
        }
        
        return $posts;
    }
    
	
    
    public static function getDiscoverPosts1($userId, $latitude=0, $longitude=0, $tagId=0, $recordCount=0, $exceptions='', $maxDistance=0, $search='')
    {    	
		$sql  = 'SELECT p.id,p.userId, p.checkinId';
    	$sql .= ',IFNULL(p.checkedInRestaurantId, 0) as checkedInRestaurantId';
    	
    	$sql .= ',IFNULL(CONCAT("' . imagePath('post') . '", p.image), "") as postImage';
    	$sql .= ',IFNULL(CONCAT("' . thumbPath('post') . '", p.image), "") as postThumb';
    	
    	$sql .= ',IFNULL(d.dishName, "") as dishName';
   	 
		$sql .= ',IFNULL(p.tip, "") as tip,p.createDate,NOW() as currentDate,IFNULL(u.userName, "") as userName';
		$sql .= ',IFNULL(u.email, "") as email,IFNULL(u.country, "") as country,IFNULL(u.state, "") as state,IFNULL(u.city, "") as city';
		$sql .= ',IFNULL(u.address, "") as address,IFNULL(u.postcode, "") as postcode';
		$sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as userImage';
		$sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as userThumb';				
		$sql .= ',IFNULL(u.facebookId, "") as facebookId,IFNULL(r.restaurantName, "") as restaurantName';

		$sql .= ' , IFNULL(r.isActivated, 0) as restaurantIsActive ';
		
		$sql .= ",DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006 as restaurantDistance";
		
// 		if($includeCount)
// 		{
		$sql .= ',(SELECT COUNT(*) FROM `like` l WHERE l.postId=p.id AND l.isDisabled=0) as likeCount';
		$sql .= ',(SELECT COUNT(*) FROM comment c WHERE c.postId=p.id AND c.isDisabled=0) as commentCount';
		$sql .= ',(SELECT COUNT(*) FROM `flag` f WHERE f.postId=p.id AND f.isDisabled=0) as flagCount';
		
		$sql .= ',(SELECT COUNT(*) FROM `bookmark` b2 WHERE b2.postId=p.id AND b2.isDisabled=0) as bookmarkCount';
		$sql .= ',(SELECT COUNT(*) FROM `bookmark` b1 WHERE b1.postId=p.id AND b1.isDisabled=0 AND b1.userId='.$userId.') as iBookark';
		
		$sql .= ',(SELECT COUNT(*) FROM `like` l2 WHERE l2.postId=p.id AND l2.isDisabled=0 AND l2.userId='.$userId.') as iLikedIt';
		$sql .= ',(SELECT COUNT(*) FROM `flag` f2 WHERE f2.postId=p.id AND f2.isDisabled=0 AND f2.userId='.$userId.') as iFlaggedIt';
// 		}
		
		$sql .= ' FROM post p INNER JOIN (SELECT  MAX(ppp.createDate) maxcreateDate, ppp.* FROM post ppp
	INNER JOIN (select checkedInRestaurantId, MAX(popularity) maxpopularity from post where checkedInRestaurantId is not null';
		
		if($tagId)
			$sql .= ' AND id IN (SELECT tm.postId FROM tagMap tm WHERE tm.tagId='.$tagId.')';
		
		if($search){
			$sql .= ' AND id IN ( SELECT r.postId FROM `dish` d inner JOIN dishReview r on r.dishId = d.id where MATCH(dishName) against ("'.$search.'")) ';
		}
		
		$sql .= ' group by checkedInRestaurantId) p2 on p2.checkedInRestaurantId = ppp.checkedInRestaurantId and p2.maxpopularity = ppp.popularity ';
		
		if($tagId)
			$sql .= ' AND id IN (SELECT tm.postId FROM tagMap tm WHERE tm.tagId='.$tagId.')';
		
		if($search){
			$sql .= ' AND id IN ( SELECT r.postId FROM `dish` d inner JOIN dishReview r on r.dishId = d.id where MATCH(dishName) against ("'.$search.'")) ';
		}
		
		$sql .= ' group by ppp.checkedInRestaurantId) p3 on p3.checkedInRestaurantId = p.checkedInRestaurantId and p3.maxcreateDate = p.createDate
	INNER JOIN restaurant r on p.checkedInRestaurantId = r.id ';
        $sql .= ' LEFT JOIN dishReview dr ON p.id = dr.postId ';
        $sql .= ' LEFT JOIN dish d ON d.id = dr.dishId ';
		$sql .= 'INNER JOIN user u ON p.userId = u.id and u.id != '.$userId.'	WHERE ';			
				
		$sql .= ' p.image IS NOT NULL';
		
		if($maxDistance > 0)
		$sql .= " AND DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006  < $maxDistance ";
// 		$sql .= ' and p.createDate  BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() ';
		$sql .= ' and p.checkedInRestaurantId is not null';
    	
		$sql .= ' AND p.image !="" AND p.isDisabled = 0 ';
		
// 		$sql .= ' AND p.checkedInRestaurantId IS NOT NULL';
		
		//        if(!empty($hashtag))
			//            $sql .= ' AND p.tip LIKE "%#' .$hashtag. '%"';
		
		
		
		if($exceptions)
			$sql .= ' AND p.id NOT IN (' .$exceptions. ')';
		
		
		$sql .= ' ORDER BY restaurantDistance ASC';
		
		if($recordCount != 0)
			$sql .= ' LIMIT ' . $recordCount;
		
		
// 		error_log($sql);
		
		$posts = Yii::app()->db->createCommand($sql)->queryAll(true);
		
		foreach ($posts as &$post)
		{
			$post['timeElapsed'] = getTimeElapsed(date_create($post['createDate']), date_create($post['currentDate']));
		}
		
		return $posts;
    	
    }
    
    public static function getDiscoverPosts($userId, $latitude=0, $longitude=0, $tagId=0, $recordCount=15, $exceptions='', $maxDistance=0, $search='', $page = 1, $days=0)
    {

    	$pagestart = ($page-1) * $recordCount;
    	
    	$sql  = 'SELECT p.id,p.userId, p.checkinId';
    	$sql .= ',IFNULL(p.checkedInRestaurantId, 0) as checkedInRestaurantId';
    	$sql .= ',IFNULL(CONCAT("' . imagePath('post') . '", p.image), "") as postImage';
    	$sql .= ',IFNULL(CONCAT("' . thumbPath('post') . '", p.image), "") as postThumb';
    	$sql .= ',IFNULL(d.dishName, "") as dishName';
    	$sql .= ',IFNULL(dr.rating, "0") as rating';
    	 
//     	if($search)
//     		$sql .= ',MATCH(dishName) AGAINST("'.$search.'" IN BOOLEAN MODE) score ';
    	 
    	$sql .= ',IFNULL(p.tip, "") as tip,p.createDate,NOW() as currentDate,IFNULL(u.userName, "") as userName';
    	$sql .= ',IFNULL(u.email, "") as email,IFNULL(u.country, "") as country,IFNULL(u.state, "") as state,IFNULL(u.city, "") as city';
    	$sql .= ',IFNULL(u.address, "") as address,IFNULL(u.postcode, "") as postcode';
    	$sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as userImage';
    	$sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as userThumb';
    	$sql .= ',IFNULL(u.facebookId, "") as facebookId,IFNULL(r.restaurantName, "") as restaurantName';
    	
    	$sql .= ' , IFNULL(r.isActivated, 0) as restaurantIsActive ';
    	
    	$sql .= ",DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006 as restaurantDistance";
    	$sql .= ',(SELECT COUNT(*) FROM `like` l WHERE l.postId=p.id AND l.isDisabled=0) as likeCount';
    	$sql .= ',(SELECT COUNT(*) FROM comment c WHERE c.postId=p.id AND c.isDisabled=0) as commentCount';
    	$sql .= ',(SELECT COUNT(*) FROM `flag` f WHERE f.postId=p.id AND f.isDisabled=0) as flagCount';
    	
    	$sql .= ',(SELECT COUNT(*) FROM `bookmark` b2 WHERE b2.postId=p.id AND b2.isDisabled=0) as bookmarkCount';
    	
    	$sql .= ',(SELECT COUNT(*) FROM `like` l2 WHERE l2.postId=p.id AND l2.isDisabled=0 AND l2.userId='.$userId.') as iLikedIt';
    	$sql .= ',(SELECT COUNT(*) FROM `flag` f2 WHERE f2.postId=p.id AND f2.isDisabled=0 AND f2.userId='.$userId.') as iFlaggedIt';
    	$sql .= ',(SELECT COUNT(*) FROM `bookmark` b1 WHERE b1.postId=p.id AND b1.isDisabled=0 AND b1.userId='.$userId.') as iBookark';
    	
    	$sql .= ' FROM post p';

    	$sql .= ' INNER JOIN';
    	
//start of temporary popular_post table{ 
    	$sql .= ' (SELECT max(p.popularity) maxpop, checkedInRestaurantId rid';
    	$sql .= ' FROM post p';
    	$sql .= ' INNER JOIN user u on u.id = p.userId AND u.isDisabled = 0 AND u.isActivated = 1 AND u.id !='.$userId;
    	$sql .= ' INNER JOIN restaurant r on r.id = p.checkedInRestaurantId AND r.isDisabled = 0 AND r.isActivated = 1';
    	$sql .= ' INNER JOIN dishReview dr on p.id = dr.postId';
    	$sql .= ' INNER JOIN dish d on d.id = dr.dishId AND d.isDisabled = 0';
    	$sql .= ' WHERE p.isDisabled = 0 ';
    	
    	if($tagId)
    		$sql .= ' AND p.id IN (SELECT tm.postId FROM tagMap tm WHERE tm.tagId='.$tagId.')';
    	if($exceptions)
    		$sql .= ' AND p.id NOT IN (' .$exceptions. ')';
    	if($days)
	    	$sql .= ' AND p.createDate  BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() ';
    	if($maxDistance > 0)
    		$sql .= " AND DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006  < $maxDistance";
    	if($search)
    		$sql .= ' AND d.dishName = "'.$search.'"';
//     		$sql .= ' AND MATCH (d.dishName) against ("'.$search.'")';
    	
    	$sql .= ' GROUP BY p.checkedInRestaurantId) pop';
//}end of temporary popular_post table

    	$sql .= ' on p.popularity = pop.maxpop AND p.checkedInRestaurantId = pop.rid';
    	
    	$sql .= ' INNER JOIN user u on u.id = p.userId AND u.isDisabled = 0 AND u.isActivated = 1';
    	$sql .= ' INNER JOIN restaurant r on r.id = p.checkedInRestaurantId AND r.isDisabled = 0 AND r.isActivated = 1';
    	$sql .= ' INNER JOIN dishReview dr on p.id = dr.postId AND dr.rating is not null';
    	$sql .= ' INNER JOIN dish d on d.id = dr.dishId AND d.isDisabled = 0';

    	
//     	if($search){
    		
// 	    	$maxScore = count(explode(' ',trim($search)));
//     		$sql .= ' AND MATCH(dishName) AGAINST("'.$search.'" IN BOOLEAN MODE) >= '.$maxScore;
//     	}
    	
    	$sql .= ' ORDER BY';
//     	if($search)
//     		$sql .= ' score desc,';
    	
    	$sql .= ' restaurantDistance ASC';
    	
//     	if($recordCount != 0)
    		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
    			
//      	error_log($sql);
    	
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
