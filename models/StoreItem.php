<?php

/**
 * This is the model class for table "storeItem".
 *
 * The followings are the available columns in table 'storeItem':
 * @property string $id
 * @property string $title
 * @property string $coverImage
 * @property string $cardImage
 * @property string $actionButtonText
 * @property string $cardActionButtonText
 * @property string $description
 * @property string $shortDescription
 * @property string $cityText
 * @property string $costType
 * @property integer $costOnline
 * @property integer $costPoints
 * @property string $termConditionsLink
 * @property string $type
 * @property string $thankYouText
 * @property string $postPurchaseInstructions
 * @property string $startDate
 * @property string $endDate
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreContest[] $storeContests
 * @property StoreEvent[] $storeEvents
 * @property StoreGoods[] $storeGoods
 * @property StoreItemCity[] $storeItemCities
 * @property StoreItemUserInfo[] $storeItemUserInfos
 * @property StoreOffer[] $storeOffers
 * @property StorePurchase[] $storePurchases
 */
class StoreItem extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeItem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, coverImage, cardImage, costType, type', 'required'),
			array('costOnline, costPoints, isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('title, coverImage, cardImage, termConditionsLink', 'length', 'max'=>255),
			array('actionButtonText, cardActionButtonText', 'length', 'max'=>50),
			array('shortDescription, disableReason', 'length', 'max'=>100),
			array('costType', 'length', 'max'=>6),
			array('type', 'length', 'max'=>7),
			array('disableReason', 'length', 'max'=>100),
			array('description, cityText, thankYouText, postPurchaseInstructions, startDate, endDate, createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, coverImage, cardImage, actionButtonText, cardActionButtonText, description, shortDescription, cityText, costType, costOnline, costPoints, termConditionsLink, type, thankYouText, postPurchaseInstructions, startDate, endDate, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'storeContests' => array(self::HAS_MANY, 'StoreContest', 'storeItemId'),
			'storeEvents' => array(self::HAS_MANY, 'StoreEvent', 'storeItemId'),
			'storeGoods' => array(self::HAS_MANY, 'StoreGoods', 'storeItemId'),
			'storeItemCities' => array(self::HAS_MANY, 'StoreItemCity', 'storeItemId'),
			'storeItemUserInfos' => array(self::HAS_MANY, 'StoreItemUserInfo', 'storeItemId'),
			'storeOffers' => array(self::HAS_MANY, 'StoreOffer', 'storeItemId'),
			'storePurchases' => array(self::HAS_MANY, 'StorePurchase', 'storeItemId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'coverImage' => 'Cover Image',
			'cardImage' => 'Card Image',
			'actionButtonText' => 'Action Button Text',
			'cardActionButtonText' => 'Card Action Button Text',
			'description' => 'Description',
			'shortDescription' => 'Short Description',
			'cityText' => 'City Text',
			'costType' => 'Cost Type',
			'costOnline' => 'Cost Online',
			'costPoints' => 'Cost Points',
			'termConditionsLink' => 'Term Conditions Link',
			'type' => 'Type',
			'thankYouText' => 'Thank You Text',
			'postPurchaseInstructions' => 'Post Purchase Instructions',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('coverImage',$this->coverImage,true);
		$criteria->compare('cardImage',$this->cardImage,true);
		$criteria->compare('actionButtonText',$this->actionButtonText,true);
		$criteria->compare('cardActionButtonText',$this->cardActionButtonText,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('shortDescription',$this->shortDescription,true);
		$criteria->compare('cityText',$this->cityText,true);
		$criteria->compare('costType',$this->costType,true);
		$criteria->compare('costOnline',$this->costOnline);
		$criteria->compare('costPoints',$this->costPoints);
		$criteria->compare('termConditionsLink',$this->termConditionsLink,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('thankYouText',$this->thankYouText,true);
		$criteria->compare('postPurchaseInstructions',$this->postPurchaseInstructions,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
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

	public static function getQuery($userId=false, $type = false, $with='all')
	{
	
		$typeOfItems = array('Event'=>'EVENT', 'Contest'=>'CONTEST', 'Offers'=>'OFFERS', 'Goods'=>'GOODS');
		
		$type = array_search(strtoupper($type), $typeOfItems);
		
		$sql = "SELECT i.id as storeItemId";
	
		$sql .= ",IFNULL(i.title, '') as title";
		$sql .= ",IFNULL(i.coverImage, '') as coverImage";
		$sql .= ",IFNULL(i.cardImage, '') as cardImage";
		$sql .= ",IFNULL(i.actionButtonText, '') as actionButtonText";
		$sql .= ",IFNULL(i.description, '') as description";
		$sql .= ",IFNULL(i.cardActionButtonText, '') as cardActionButtonText";
		$sql .= ",IFNULL(i.shortDescription, '') as shortDescription";
		$sql .= ",IFNULL(i.cityText, '') as cityText";
		
		$sql .= ",IFNULL(i.costType, '') as costType";
		$sql .= ",IFNULL(i.costOnline, '') as costOnline";
		$sql .= ",IFNULL(i.costPoints, '') as costPoints";
		$sql .= ",IFNULL(i.termConditionsLink, '') as termConditionsLink";
		$sql .= ",IFNULL(i.type, '') as type";
		$sql .= ",IFNULL(i.thankYouText, '') as thankYouText";
		$sql .= ",IFNULL(i.postPurchaseInstructions, '') as postPurchaseInstructions";
		$sql .= ",IFNULL(i.startDate, '') as startDate";
		$sql .= ",IFNULL(i.endDate, '') as endDate";
		
		
		
		if($with == 'Purchase'){
			$sql .= ",IFNULL(p.userId, '') as userId";
			$sql .= ",IFNULL(p.quantity, '') as quantity";
			$sql .= ",IFNULL(p.costOnline, '') as paidCostOnline";
			$sql .= ",IFNULL(p.costPoints, '') as paidCostPoints";
			$sql .= ",IFNULL(p.metaData, '') as metaData";
			$sql .= ",IFNULL(p.createDate, '') as createDate";
		}
		elseif($userId > 0)
			$sql .= ',(SELECT COUNT(*) FROM `storePurchase` p WHERE i.id = p.storeItemId  AND p.isDisabled=0 AND p.userId='.$userId.') as iPurchasedIt';
		
		if($type)
			$sql .= ",IFNULL(t.id, '') as entityId";
			
		// 		$sql .= ",IFNULL(i.isDisabled, '') as isDisabled";
		// 		$sql .= ",IFNULL(i.disableReason, '') as disableReason";
		// 		$sql .= ",IFNULL(i.createDate, '') as createDate";
		// 		$sql .= ",IFNULL(i.updateDate, '') as updateDate";
		// 		$sql .= ",IFNULL(i.createId, '') as createId";
		// 		$sql .= ",IFNULL(i.updateId, '') as updateId";

		$sql .= " FROM storeItem i ";
		
		if($type)
			$sql .= " INNER JOIN store$type t on i.id = t.storeItemId and i.type = '".strtoupper($type)."' ";
		
		if($with == 'Purchase')
			$sql .= " INNER JOIN storePurchase p on i.id = p.storeItemId and p.userId = ".$userId ;
		
// 		$sql .= ",IFNULL(e.dateTime, '') as 'dateTime'";
// 		$sql .= ",IFNULL(e.venue, '') as 'venue'";
// 		$sql .= ",IFNULL(e.TotalSeats, '') as 'TotalSeats'";
// 		$sql .= ",IFNULL(e.avilableSeat, '') as 'avilableSeat'";
// 		$sql .= ",IFNULL(e.earlyBirdPaymentLink, '') as 'earlyBirdPaymentLink'";
// 		$sql .= ",subType ";
// 		$sql .= ",IFNULL(e.paymentLink, '') as 'paymentLink'";
// 		$sql .= ",IFNULL(e.storeItemId, '') as 'storeItemId'";
// 		$sql .= " FROM storeEvent e  ";
// 		$sql .= " INNER JOIN storeItem i on i.id = e.storeItemId and i.type = 'EVENT' ";
	
		return $sql;
	}
	
	
	
	public static function getStorePurchase($page=1, $userId = false, $type=false, $option = array(), $recordCount = 24){
		
		$pagestart = ($page-1) * $recordCount;
		
		$sql = self::getQuery($userId, $type, 'Purchase');
		
// 		if($status != 'all'){
// 			$sql .= ' WHERE e.dateTime > now() and i.startDate < now() and i.endDate > now() ';
			$sql .= ' and i.isDisabled = 0 ';
// 		}
		
		$sql .= ' ORDER BY p.createDate DESC';
		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
		
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
		
	}
	
	
	public static function getStoreItems($page=1, $userId, $type=false, $option = array(), $recordCount = 24){
		
		$pagestart = ($page-1) * $recordCount;
		
		$sql = self::getQuery($userId, $type);
		
		$sql .= ' WHERE i.startDate < now() and i.endDate > now() ';
		$sql .= ' and i.isDisabled = 0 ';
		
		$sql .= ' ORDER BY i.createDate DESC';
		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
		
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
	}
	
	
	public static function getThisItem($storeItemId, $userId, $type=false, $option = array()){
	
	
		$sql = self::getQuery($userId, $type);
	
		$sql .= ' WHERE i.isDisabled = 0 ';
		$sql .= ' and i.id = '.$storeItemId;
	
		$sql .= ' LIMIT 1';
	
		$result = Yii::app()->db->createCommand($sql)->queryRow(true);
		return $result;
	}
	
	
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
