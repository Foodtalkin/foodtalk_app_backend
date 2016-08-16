<?php

/**
 * This is the model class for table "adwords".
 *
 * The followings are the available columns in table 'adwords':
 * @property string $id
 * @property integer $entityId
 * @property string $title
 * @property string $image
 * @property integer $points
 * @property string $couponCode
 * @property string $paymentUrl
 * @property string $description
 * @property integer $totalSlots
 * @property integer $bookedSlots
 * @property string $description2
 * @property string $expiry
 * @property string $type
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 */
class Adwords extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'adwords';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, image, description, type', 'required'),
			array('entityId, points, totalSlots, bookedSlots, isDisabled', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('image, paymentUrl', 'length', 'max'=>255),
			array('couponCode', 'length', 'max'=>50),
			array('type, createId, updateId', 'length', 'max'=>10),
			array('disableReason', 'length', 'max'=>128),
			array('description2, expiry, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, entityId, title, image, points, couponCode, paymentUrl, description, totalSlots, bookedSlots, description2, expiry, type, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'entityId' => 'Entity',
			'title' => 'Title',
			'image' => 'Image',
			'points' => 'Points',
			'couponCode' => 'Coupon Code',
			'paymentUrl' => 'Payment Url',
			'description' => 'Description',
			'totalSlots' => 'Total Slots',
			'bookedSlots' => 'Booked Slots',
			'description2' => 'Description2',
			'expiry' => 'Expiry',
			'type' => 'Type',
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
		$criteria->compare('entityId',$this->entityId);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('points',$this->points);
		$criteria->compare('couponCode',$this->couponCode,true);
		$criteria->compare('paymentUrl',$this->paymentUrl,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('totalSlots',$this->totalSlots);
		$criteria->compare('bookedSlots',$this->bookedSlots);
		$criteria->compare('description2',$this->description2,true);
		$criteria->compare('expiry',$this->expiry,true);
		$criteria->compare('type',$this->type,true);
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
	 * @return Adwords the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
  public static function getQuery($userId = null, $all = false)
    {
        $sql = 'SELECT ad.id';
        
        $sql .= ',IFNULL(ad.entityId , "") as entityId';
        $sql .= ',ad.title';
        $sql .= ',IFNULL(CONCAT("' . imagePath('post') . '", ad.image), "") as adImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('post') . '", ad.image), "") as adThumb';
        $sql .= ',IFNULL(ad.points , "") as points';
        $sql .= ',IFNULL(ad.couponCode , "") as couponCode';
        $sql .= ',IFNULL(ad.paymentUrl , "") as paymentUrl';
        $sql .= ',IFNULL(ad.description , "") as description';
        $sql .= ',IFNULL(ad.totalSlots , "") as totalSlots';
        $sql .= ',IFNULL(ad.bookedSlots , "") as bookedSlots';
        $sql .= ',IFNULL(ad.description2 , "") as description2';
        $sql .= ',IFNULL(ad.expiry , "") as expiry';
        
        if($all &&  $userId > 0){        	
        	$sql .= ',IFNULL(rp.id , 0) as rid';
        	$sql .= ',IFNULL(rp.pointsRedeemed , "0") as pointsRedeemed';
	        $sql .= ',IF(rp.id > 0, 1 , 0 ) as iRedeemed';        
        	$sql .= ',IFNULL(rp.createDate , "-") as bookedOn';
        }
		elseif($userId > 0)
        $sql .= ',IF((SELECT COUNT(*) FROM `redeemPoints` rp WHERE rp.redeemFor = ad.type and ad.id = rp.entityId  and  rp.userId = '.$userId.') > 0, 1 , 0 ) as iRedeemed';
        
        
        $sql .= ',ad.type';
        $sql .= ' FROM adwords ad';
        
        if($all &&  $userId > 0)
        	$sql .= ' INNER JOIN redeemPoints rp on rp.redeemFor = ad.type and ad.id = rp.entityId  and  rp.userId = '.$userId;
        
        return $sql;
    }
    
    
    public static function getRedeemdAddsByUser($userId){
    	
    	$sql = self::getQuery($userId, true);
    	$sql .= ' WHERE ad.expiry >now() ';
    	$ads = Yii::app()->db->createCommand($sql)->queryAll(true);
    	return $ads;
    	
    }
    
    
    public static function getAvailability($adId, $userId, $quantity = 1){
    	
    	$sql = 'SELECT u.id uId, ad.id as adId, userName, ad.type,  u.facebookId, IFNULL(avilablePoints, 0) as avilablePoints , IFNULL(ad.points * '.$quantity.' , 0) as points, ad.totalSlots - ad.bookedSlots as avilableSlots FROM `user`u LEFT JOIN activityScore a on a.facebookId = u.facebookId';
    	$sql .= " INNER JOIN adwords ad WHERE u.id = $userId and ad.id = $adId limit 1";
    	
    	$result = Yii::app()->db->createCommand($sql)->queryRow(true);
    	return $result;
    }
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public static function getAds($userId = null, $type = false)
    {
        //fetch all contactUs records of a specific user
        $sql = self::getQuery($userId);
        $sql .= ' WHERE ad.isDisabled=0  and ad.expiry >now() ';
        
        if($type){
        	$sql .=  ' AND ad.type = "' .$type.'" ';
        }
        
        $ads = Yii::app()->db->createCommand($sql)->queryAll(true);
        
        return $ads;
    }
	
	
}
