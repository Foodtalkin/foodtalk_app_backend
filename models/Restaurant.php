<?php

/**
 * This is the model class for table "restaurant".
 *
 * The followings are the available columns in table 'restaurant':
 * @property string $id
 * @property integer $duplicateId
 * @property string $role
 * @property string $restaurantName
 * @property string $email
 * @property string $password
 * @property string $contactName
 * @property string $country
 * @property string $state
 * @property string $cityId
 * @property string $area
 * @property string $region
 * @property string $address
 * @property string $postcode
 * @property double $latitude
 * @property double $longitude
 * @property integer $verified
 * @property integer $suggested
 * @property string $phone1
 * @property string $phone2
 * @property string $highlights
 * @property integer $homeDelivery
 * @property integer $veg
 * @property integer $nonVeg
 * @property integer $dineIn
 * @property integer $seating
 * @property integer $outdoorSeating
 * @property integer $airConditioned
 * @property integer $lounge
 * @property integer $serveAlcohol
 * @property integer $microbrewery
 * @property integer $fullBar
 * @property integer $pub
 * @property integer $nightClub
 * @property integer $smokingZone
 * @property integer $sheesha
 * @property integer $wifi
 * @property integer $liveMusic
 * @property integer $entryFee
 * @property string $priceRange
 * @property string $timing
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
 * @property string $activationCode
 * @property integer $isActivated
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 * @property string $regions
 *
 * The followings are the available model relations:
 * @property City $city
 * @property Favourite[] $favourites
 * @property Post[] $posts
 * @property RestaurantCuisine[] $restaurantCuisines
 */
class Restaurant extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
	public function tableName()
	{
		return 'restaurant';
	}
	

	protected function afterSave()
	{		 
		if(isset($this->isDisabled) && $this->isDisabled==1){
			es('','/foodtalkindex/restaurant/'.$this->id ,'DELETE');
		}
		
		
		if($this->isNewRecord){
			
			$esRestaurant = array(
					"id" => $this->id,
					"role" => "restaurant",
					"restaurantname" => $this->restaurantName,
					"cityid" => $this->cityId,
					"cityname" => $this->city->cityName,
					"stateid" => $this->city->stateId,
					"countryid" => $this->city->countryId,
					"address" =>$this->address,
					"area" =>$this->area,

					"regionid" => $this->city->regionId,
					"regionname" => $this->city->region->name,
					"isactivated" => false,
					"suggested" => false,
					"timing" => '',
					"pricerange" => '',
					"popularity"=> 0
			);
			
			if($this->latitude > 0)
			$esRestaurant["location"] = $this->latitude.", ".$this->longitude;
			
		     $res =	es(json_encode($esRestaurant),'/foodtalkindex/restaurant/'.$this->id ,'POST');
		     
// 		     var_dump($res);
			}
		 
		return parent::afterSave();
	}
	
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cityId, homeDelivery, veg, nonVeg, dineIn, seating, suggested, outdoorSeating, airConditioned, lounge, serveAlcohol, microbrewery, fullBar, pub, nightClub, smokingZone, sheesha, wifi, liveMusic, entryFee, isActivated, isDisabled', 'numerical', 'integerOnly'=>true),
            array('latitude, longitude', 'numerical'),
            array('role', 'length', 'max'=>16),
            array('restaurantName, email, contactName, area, address, image, disableReason, timing', 'length', 'max'=>128),
            array('password', 'length', 'max'=>40),
            array('country, state, priceRange', 'length', 'max'=>50),
            array('region, postcode, phone1, phone2', 'length', 'max'=>20),
            array('highlights', 'length', 'max'=>500),
            array('facebookId, facebookLink', 'length', 'max'=>32),
            array('twitterId, googleId, linkedinId, twitterLink, googleLink, linkedinLink', 'length', 'max'=>255),
            array('webAddress', 'length', 'max'=>250),
            array('activationCode, createId, updateId', 'length', 'max'=>10),
            array('updateDate', 'safe'),
            
            // rules for image
            array('image', 'file', 'allowEmpty' => true, 'maxSize' => 1024*1024*1, 'types' => 'jpg, jpeg, png', 'tooLarge' => "The file {file} is too large. Its size can not exceed 1 MB"),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, role, restaurantName, email, password, contactName, country, state, cityId area, address, postcode, latitude, longitude, phone1, phone2, highlights, priceRange, timing, image, facebookId, twitterId, googleId, linkedinId, facebookLink, twitterLink, googleLink, linkedinLink, webAddress, activationCode, isActivated, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'cityId'),
            'favourites' => array(self::HAS_MANY, 'Favourite', 'restaurantId'),
            'posts' => array(self::HAS_MANY, 'Post', 'checkedInRestaurantId'),
            'restaurantCuisines' => array(self::HAS_MANY, 'RestaurantCuisine', 'restaurantId'),
        	'restaurantReport' => array(self::HAS_MANY, 'RestaurantReport', 'restaurantId'),
        	'reportedCount' => array(self::STAT, 'RestaurantReport', 'restaurantId'),
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
	            'restaurantName' => 'Restaurant Name',
	            'email' => 'Email',
	            'password' => 'Password',
	            'contactName' => 'Contact Name',
	            'country' => 'Country',
	            'state' => 'State',
				'cityId' => 'City',       		
	            'area' => 'Area',
	        	'region' => 'Region',
	        	'suggested' => 'Suggested',	        		
	            'address' => 'Address',
	            'postcode' => 'Postcode',
	            'latitude' => 'Latitude',
	            'longitude' => 'Longitude',
	            'phone1' => 'Phone1',
	            'phone2' => 'Phone2',
	            'highlights' => 'Highlights',
	            'homeDelivery' => 'Home Delivery',
	            'veg' => 'Veg',
	            'nonVeg' => 'Non Veg',
	            'dineIn' => 'Dine In',
	            'seating' => 'Seating',
	            'outdoorSeating' => 'Outdoor Seating',
	            'airConditioned' => 'Air Conditioned',
	            'lounge' => 'Lounge',
	            'serveAlcohol' => 'Serve Alcohol',
	            'microbrewery' => 'Microbrewery',
	            'fullBar' => 'Full Bar',
	            'pub' => 'Pub',
	            'nightClub' => 'Night Club',
	            'smokingZone' => 'Smoking Zone',
	            'sheesha' => 'Sheesha',
	            'wifi' => 'Wifi',
	            'liveMusic' => 'Live Music',
	            'entryFee' => 'Entry Fee',
	            'priceRange' => 'Price Range',
	            'timing' => 'Timing',
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
    public function search($type=false)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('role',$this->role,true);
        $criteria->compare('restaurantName',$this->restaurantName,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('contactName',$this->contactName,true);
        $criteria->compare('country',$this->country,true);
        $criteria->compare('state',$this->state,true);
//         $criteria->compare('city',$this->city,true);
        $criteria->compare('area',$this->area,true);
        $criteria->compare('region',$this->region,true);
        
        $criteria->compare('suggested',$this->suggested,true);
        
        
        
        $criteria->compare('address',$this->address,true);
        $criteria->compare('postcode',$this->postcode,true);
        $criteria->compare('latitude',$this->latitude);
        $criteria->compare('longitude',$this->longitude);
        $criteria->compare('phone1',$this->phone1,true);
        $criteria->compare('phone2',$this->phone2,true);
        $criteria->compare('highlights',$this->highlights,true);
        $criteria->compare('homeDelivery',$this->homeDelivery);
        $criteria->compare('veg',$this->veg);
        $criteria->compare('nonVeg',$this->nonVeg);
        $criteria->compare('dineIn',$this->dineIn);
        $criteria->compare('seating',$this->seating);
        $criteria->compare('outdoorSeating',$this->outdoorSeating);
        $criteria->compare('airConditioned',$this->airConditioned);
        $criteria->compare('lounge',$this->lounge);
        $criteria->compare('serveAlcohol',$this->serveAlcohol);
        $criteria->compare('microbrewery',$this->microbrewery);
        $criteria->compare('fullBar',$this->fullBar);
        $criteria->compare('pub',$this->pub);
        $criteria->compare('nightClub',$this->nightClub);
        $criteria->compare('smokingZone',$this->smokingZone);
        $criteria->compare('sheesha',$this->sheesha);
        $criteria->compare('wifi',$this->wifi);
        $criteria->compare('liveMusic',$this->liveMusic);
        $criteria->compare('entryFee',$this->entryFee);
        $criteria->compare('priceRange',$this->priceRange,true);
        $criteria->compare('timing',$this->timing,true);
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
        $criteria->compare('activationCode',$this->activationCode,true);
        $criteria->compare('isActivated',$this->isActivated);
        $criteria->compare('isDisabled',$this->isDisabled);
        $criteria->compare('disableReason',$this->disableReason,true);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        
//         'restaurant.verified',
//         'restaurant.unverified',
//         'restaurant.inactive',
//         'restaurant.disabled',

        
//         if(isset($_SESSION['region']))
//         	$criteria->addCondition("t.region = '".$_SESSION['region']."'");        
        
		if(isset($_SESSION['region'])){
        	if ($_SESSION['region']['id']=='rest'){
        		$criteria->join =" left JOIN city ct ON ct.id = t.cityId ";
        		$criteria->addCondition("ct.regionId is null or ct.id is null");
        	}
        	else 
        		$criteria->join =" Inner JOIN city ct ON ct.id = t.cityId and ct.regionId = ".$_SESSION['region']['id'];
        }
        
        
        switch (Yii::app()->controller->action->id){
        	case 'disabled':
        		$criteria->addCondition("t.isDisabled = 1");
        		break;
        	case 'verified':
        		$criteria->addCondition("t.isActivated = 1");
        		$criteria->addCondition("t.verified = 1");
        		$criteria->addCondition("t.isDisabled = 0");
        		
        		break;
        	case 'unverified':
        		$criteria->addCondition("t.isActivated = 1");
        		$criteria->addCondition("t.verified = 0");
        		$criteria->addCondition("t.isDisabled = 0");
        		
        		break;
      		case 'inactive':
       			$criteria->addCondition("t.isActivated = 0");
//        			$criteria->addCondition("t.verified = 1");
       			$criteria->addCondition("t.isDisabled = 0");
        		
       			break;
       			
       			
    		case 'duplicate':
    			$criteria->addCondition("t.duplicateId is not null");
//        			$criteria->addCondition("t.isActivated = 0");
//        			     $criteria->addCondition("t.verified = 1");
       			$criteria->addCondition("t.isDisabled = 1");
       			
       				break;
    		case 'foodtalkSuggested':
    			$criteria->addCondition("t.isActivated = 1");
        		$criteria->addCondition("t.isDisabled = 0");
        		$criteria->addCondition("t.suggested = 1");
        		break;
       				
       				
       				
        	default:
        		$criteria->addCondition("t.isActivated = 1");
        		$criteria->addCondition("t.isDisabled = 0");
        }
        
//  		if(Yii::app()->controller->action->id=='disabled')
//  			$criteria->addCondition("t.isDisabled = 1");
//  		else
// 	        $criteria->addCondition("t.isDisabled = 0");
        
//  		echo '$type : '.$type;
 		
        if($type){

//         	$criteria->compare('cnt',$this->cnt,true);        	 
//         	$criteria->select = 't.*';        	
//         	$criteria->group = 't.id';
        	
        	$criteria->join =" Inner JOIN restaurantReport rr ON rr.restaurantId = t.id ";
        	
        	switch ($type){
        		case 1:
        			$criteria->addCondition(" reportType =1 ");       		
        		break;
        		case 2:
        			$criteria->addCondition(" reportType =2 ");
        		break;
        		case 3:
        			$criteria->addCondition(" reportType =3 ");
        		break;
        	}        	
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        	'sort'=>array(
        		'defaultOrder'=>'createDate desc'
        	),
        	'pagination'=>array(
        		'pageSize'=>100
        	)
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Restaurant the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get restaurant records
     */
    public static function getQuery($userId, $latitude=0, $longitude=0, $includeCuisine = true, $includeCount = false)
    {
        $sql = 'SELECT r.id';
        $sql .= ',IFNULL(r.restaurantName, "") as restaurantName';
        $sql .= ',IFNULL(r.email, "") as email';
        $sql .= ',IFNULL(r.contactName, "") as contactName';
        $sql .= ',IFNULL(r.country, "") as country';
        $sql .= ',IFNULL(r.state, "") as state';
//         $sql .= ',IFNULL(r.city, "") as city';
        $sql .= ',IFNULL(r.area, "") as area';
        $sql .= ',IFNULL(r.region, "") as region';
        $sql .= ',IFNULL(r.address, "") as address';
        $sql .= ',IFNULL(r.postcode, "") as postcode';
        $sql .= ',IFNULL(r.latitude, 0) as latitude';
        $sql .= ',IFNULL(r.longitude, 0) as longitude';
        $sql .= ',IFNULL(r.phone1, "") as phone1';
        $sql .= ',IFNULL(r.phone2, "") as phone2';
        $sql .= ',IFNULL(r.highlights, "") as highlights';
        $sql .= ',r.homeDelivery';
//        $sql .= ',r.veg';
        $sql .= ',r.nonVeg';
        $sql .= ',r.dineIn';
//        $sql .= ',r.seating';
//        $sql .= ',r.outdoorSeating';
//        $sql .= ',r.airConditioned';
//        $sql .= ',r.lounge';
//        $sql .= ',r.serveAlcohol';
//        $sql .= ',r.microbrewery';
        $sql .= ',r.fullBar';
//        $sql .= ',r.pub';
//        $sql .= ',r.nightClub';
//        $sql .= ',r.smokingZone';
//        $sql .= ',r.sheesha';
        $sql .= ',r.wifi';
        $sql .= ',r.isActivated as restaurantIsActive';
//        $sql .= ',r.liveMusic';
//        $sql .= ',r.entryFee';
        $sql .= ',IFNULL(r.priceRange, "") as priceRange';
        $sql .= ',IFNULL(r.timing, "") as timing';
        $sql .= ',IFNULL(CONCAT("' . imagePath('restaurant') . '", r.image), "") as image';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('restaurant') . '", r.image), "") as thumb';
        $sql .= ',IFNULL(r.facebookId, "") as facebookId';
        $sql .= ',IFNULL(r.twitterId, "") as twitterId';
        $sql .= ',IFNULL(r.googleId, "") as googleId';
        $sql .= ',IFNULL(r.linkedinId, "") as linkedinId';
        $sql .= ',IFNULL(r.facebookLink, "") as facebookLink';
        $sql .= ',IFNULL(r.twitterLink, "") as twitterLink';
        $sql .= ',IFNULL(r.googleLink, "") as googleLink';
        $sql .= ',IFNULL(r.linkedinLink, "") as linkedinLink';
        $sql .= ',IFNULL(r.webAddress, "") as webAddress';
        
        if($latitude == 0 || $longitude==0)
            $sql .= ',0 as distance';
        else
            //$sql .= ",RADIANS(ACOS(SIN(DEGREES($latitude)) * SIN(DEGREES(r.latitude)) + COS(DEGREES($latitude)) * COS(DEGREES(r.latitude)) * COS(DEGREES($longitude - r.longitude)))) * 111189.577 as distance";
            $sql .= ",DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006 as distance";
        
        if($includeCuisine)
            $sql .= ',(SELECT IFNULL(GROUP_CONCAT(c.cuisineName), "") FROM cuisine c JOIN restaurantCuisine rc on c.id = rc.cuisineId WHERE rc.restaurantId=r.id) as cuisines';
        
        if($includeCount)
        {
//             $sql .= ',(SELECT COUNT(*) FROM `post` p WHERE p.checkedInRestaurantId=r.id AND p.isDisabled=0) as checkInCount';
            
            $sql .= ',(SELECT COUNT(distinct ch.id) FROM `checkins` ch inner join post on ch.id = post.checkinId WHERE ch.restaurantId=r.id and post.isDisabled = 0) as checkInCount';
            $sql .= ',(SELECT COUNT(*) FROM `favourite` f WHERE f.restaurantId=r.id) as favouriteCount';
            $sql .= ',(SELECT COUNT(*) FROM `favourite` f WHERE f.restaurantId=r.id AND f.userId='.$userId.') as isMyFavourite';
        }
        
        $sql .= ' , IFNULL(city.cityName, "") as cityName, IFNULL(city.id, "") as cityId, IFNULL(city.stateId , "") as stateId, IFNULL(city.countryId , "") as countryId, IFNULL(city.regionId , "") as regionId';
        $sql .= ' FROM restaurant r left join city on city.id = r.cityId';
        
        return $sql;
    }
    
    /**
     * Returns restaurant profile
     */
    public static function getProfileById($userId, $restaurantId, $latitude=0, $longitude=0, $includeCuisine = true, $includeCount = true)
    {
        $sql = self::getQuery($userId, $latitude, $longitude, $includeCuisine, $includeCount);
        $sql .= ' WHERE r.id=' . $restaurantId;
        $sql .= ' and r.isActivated = 1';
        
//         echo $sql;
        $profile = Yii::app()->db->createCommand($sql)->queryRow(true);
        return $profile;
    }
    
    /**
     * Returns restaurant records
     */
    public static function getRestaurants($userId, $latitude=0, $longitude=0, $includeCuisine = true, $includeCount = false, $searchText='', $recordCount=0, $exceptions='', $maxDistance=0, $region='', $foodtalksuggested=0)
    {
        $sql = self::getQuery($userId, $latitude, $longitude, $includeCuisine, $includeCount);
        $sql .= " WHERE r.isDisabled = 0";
        
        if(!empty($searchText))
            $sql .= ' AND r.restaurantName LIKE "%' .$searchText. '%"';
        
//         if(!empty($region))
//         	$sql .= ' AND r.region = "' .$region. '"';
        
        if($exceptions)
            $sql .= ' AND r.id NOT IN (' .$exceptions. ')';
        
        if($maxDistance)
            $sql .= " AND DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(r.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(r.latitude)) * COS(RADIANS($longitude - r.longitude)))) * 111189.3006 <= $maxDistance";
		else 
			$sql .= ' AND r.isActivated = 1 ';
        
		if($foodtalksuggested)
			$sql .= ' AND r.suggested = 1 ';
		
        
        $sql .= ' ORDER BY distance ASC';
        
        if($foodtalksuggested)
        	$sql .= ' LIMIT 30';
        elseif($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $restaurants = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $restaurants;
    }
    
    /**
     * Returns restaurant names
     */
    public static function getRestaurantsName($searchText='', $recordCount)
    {
        $sql = empty($searchText) ? '' : '(';
        
        $sql .= 'SELECT r.id';
        $sql .= ',IFNULL(r.restaurantName, "") as restaurantName';
        $sql .= ' FROM restaurant r';
        $sql .= " WHERE r.isDisabled = 0";
        
        if(!empty($searchText))
        {
            $sql .= ' AND r.restaurantName LIKE "' .$searchText. '%"';
            $sql .= ' ORDER BY r.restaurantName ASC)';
            
            $sql .= ' UNION';
            $sql .= ' (SELECT r2.id';
            $sql .= ',IFNULL(r2.restaurantName, "") as restaurantName';
            $sql .= ' FROM restaurant r2';
            $sql .= " WHERE r2.isDisabled = 0";
            $sql .= ' AND r2.restaurantName LIKE "_%' .$searchText. '%"';
            $sql .= ' ORDER BY r2.restaurantName ASC LIMIT 10)';
        }
        
        //$sql .= ' ORDER BY r.restaurantName ASC';
        
        if($recordCount != 0)
            $sql .= ' LIMIT ' . $recordCount;
        
        $restaurants = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $restaurants;
    }
}
