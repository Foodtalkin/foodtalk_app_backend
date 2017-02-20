<?php

/**
 * This is the model class for table "storeOffer".
 *
 * The followings are the available columns in table 'storeOffer':
 * @property string $id
 * @property string $validTill
 * @property integer $totalQuantity
 * @property integer $availableQuantity
 * @property integer $limitPerUser
 * @property string $couponCode
 * @property string $subType
 * @property string $redemptionUrl
 * @property string $storeItemId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreCoupon[] $storeCoupons
 * @property StoreItem $storeItem
 */
class StoreOffer extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeOffer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subType, storeItemId', 'required'),
			array('totalQuantity, availableQuantity, limitPerUser, isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('couponCode', 'length', 'max'=>100),
			array('subType, storeItemId', 'length', 'max'=>11),
			array('redemptionUrl', 'length', 'max'=>300),
			array('disableReason', 'length', 'max'=>200),
			array('validTill, createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, validTill, totalQuantity, availableQuantity, limitPerUser, couponCode, subType, redemptionUrl, storeItemId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'storeCoupons' => array(self::HAS_MANY, 'StoreCoupon', 'storeOfferId'),
			'storeItem' => array(self::BELONGS_TO, 'StoreItem', 'storeItemId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'validTill' => 'Valid Till',
			'totalQuantity' => 'Total Quantity',
			'availableQuantity' => 'Available Quantity',
			'limitPerUser' => 'Limit Per User',
			'couponCode' => 'Coupon Code',
			'subType' => 'Sub Type',
			'redemptionUrl' => 'Redemption Url',
			'storeItemId' => 'Store Item',
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
		$criteria->compare('validTill',$this->validTill,true);
		$criteria->compare('totalQuantity',$this->totalQuantity);
		$criteria->compare('availableQuantity',$this->availableQuantity);
		$criteria->compare('limitPerUser',$this->limitPerUser);
		$criteria->compare('couponCode',$this->couponCode,true);
		$criteria->compare('subType',$this->subType,true);
		$criteria->compare('redemptionUrl',$this->redemptionUrl,true);
		$criteria->compare('storeItemId',$this->storeItemId,true);
		$criteria->compare('isDisabled',$this->isDisabled);
		$criteria->compare('disableReason',$this->disableReason,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId);
		$criteria->compare('updateId',$this->updateId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
	public static function getQuery($userId=0)
	{
	
		$sql = "SELECT o.id";
	
		$sql .= ",i.id as storeItemId ";
		$sql .= ",IFNULL(i.title, '') as title";
		$sql .= ",IFNULL(i.coverImage, '') as coverImage";
		$sql .= ",IFNULL(i.cardImage, '') as cardImage";
		$sql .= ",IFNULL(i.actionButtonText, '') as actionButtonText";
		$sql .= ",IFNULL(i.cardActionButtonText, '') as cardActionButtonText";
		$sql .= ",IFNULL(i.description, '') as description";
		$sql .= ",IFNULL(i.shortDescription, '') as shortDescription";
		$sql .= ",IFNULL(i.cityText, '') as cityText";
		$sql .= ",IFNULL(i.costType, '') as costType";
		$sql .= ",IFNULL(i.costOnline, '') as costOnline";
		$sql .= ",IFNULL(i.costPoints, '') as costPoints";
		$sql .= ",IFNULL(i.termConditionsLink, '') as termConditionsLink";
		$sql .= ",IFNULL(i.thankYouText, '') as thankYouText";
		$sql .= ",IFNULL(i.postPurchaseInstructions, '') as postPurchaseInstructions";
		$sql .= ",IFNULL(i.startDate, '') as startDate";
		$sql .= ",IFNULL(i.endDate, '') as endDate";
		$sql .= ",IFNULL(i.type, '') as type";

		if($userId > 0)
			$sql .= ',(SELECT COUNT(*) FROM `storePurchase` p WHERE i.id = p.storeItemId  AND p.isDisabled=0 AND p.userId='.$userId.') as iPurchasedIt';
		
		$sql .= ', IF(o.subType = "UNIQUE_CODE", (SELECT COUNT(1) FROM storeCoupon c WHERE c.storeOfferId = o.id and c.isUsed = 1 ) , "N/A") as totalRedeemed';	
		$sql .= ",IFNULL(o.validTill, '') as 'validTill'";
		$sql .= ",IFNULL(o.totalQuantity, '') as 'totalQuantity'";
		$sql .= ",IFNULL(o.availableQuantity, '') as 'availableQuantity'";
		$sql .= ",IFNULL(o.limitPerUser, '') as 'limitPerUser'";
		$sql .= ",IFNULL(o.couponCode, '') as 'couponCode'";
		$sql .= ",IFNULL(o.autoGenerateCode, '') as 'autoGenerateCode'";
		
		$sql .= ",subType ";
		$sql .= ",IFNULL(o.redemptionUrl, '') as 'redemptionUrl'";
		$sql .= ",IFNULL(o.redemptionPhone, '') as 'redemptionPhone'";
		$sql .= ",IFNULL(o.storeItemId, '') as 'storeItemId'";
		$sql .= ",IFNULL(o.isDisabled, '') as 'isDisabled'";
	
		$sql .= " FROM storeOffer o ";
		$sql .= " INNER JOIN storeItem i on i.id = o.storeItemId ";
// 		$sql .= " INNER JOIN storeItem i on i.id = o.storeItemId and i.type = 'OFFER' ";
		
		return $sql;
	}
	
	public static function getOffers($page=1, $status = 'upcomming', $options=array(), $recordCount=9 ){
	
		$recordCount = (self::$MAXRecordCount < $recordCount ? self::$MAXRecordCount : $recordCount);
		$pagestart = ($page-1) * $recordCount;
	
		$sql = self::getQuery();

		if(isset($options['restaurantId']))
			$sql .= ' INNER JOIN storeItemRestaurant r on r.storeItemId = o.storeItemId and r.restaurantId = '.$options['restaurantId'];
		
		$where = [];
// 		if($status != 'all'){
			
// 			$sql .= ' WHERE o.validTill > now() and i.startDate < now() and i.endDate > now() ';
// 			$sql .= ' and i.isDisabled = 0 ';
// 		}
		
		switch (strtolower($status)){
			case 'active':
				$where[] = 'o.validTill > now()';
				$where[] = 'i.startDate < now()';
				$where[] = 'i.endDate > now()';
				$where[] = 'i.isDisabled = 0';
				break;
			case 'disabled':
				$where[] = 'i.isDisabled = 1';
				break;
			case 'past':
				$where[] = 'o.validTill < now()';
				$where[] = 'i.endDate < now()';
				$where[] = 'i.isDisabled = 0';
				break;
			case 'all':
				break;
			default:
				$where[] = 'o.validTill > now()';
				$where[] = 'i.startDate < now()';
				$where[] = 'i.endDate > now()';
				$where[] = 'i.isDisabled = 0';
		}

		if(isset($options['type'])){
			$where[] = 'i.type like "'.$options['type'].'"';
// 			$sql .= ' and i.type like "'.$options['type'].'"';
		}
		if(isset($options['ids'])){
			$where[] = ' o.id in ('.$options['ids'].')';
// 			$sql .= ' and o.id in ('.$options['ids'].')';
		}
		if(isset($options['searchText'])){
			$where[] = ' i.title like "%'.$options['searchText'].'%"';
// 			$sql .= ' and i.title like "%'.$options['searchText'].'%"';
		}

		$sql .= empty($where)? ' ':' WHERE '.implode(' and ', $where);
		
		$sql .= ' ORDER BY o.createDate DESC';
		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
		
// 		echo $sql;
// 		die('DIE');
		
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
	
	}
	
	
	public static function getThisOffer($id=0, $storeItemId=0, $status = 'upcomming', $options=array()){
	
		if(isset($options['userId']) && $options['userId'] > 0)
			$userId = $options['userId'];
		else 
			$userId = 0;
		
		$sql = self::getQuery($userId);
		
		if($id > 0)
			$sql .= ' WHERE o.id = '.$id;
		else
			$sql .= ' WHERE o.storeItemId = '.$storeItemId;
	
		if($status != 'all'){
			$sql .= ' AND i.isDisabled = 0 ';
		}
	
		$sql .= ' LIMIT 1';
	
		$result = Yii::app()->db->createCommand($sql)->queryRow(true);
		return $result;
	
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreOffer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
