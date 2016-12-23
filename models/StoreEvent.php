<?php

/**
 * This is the model class for table "storeEvent".
 *
 * The followings are the available columns in table 'storeEvent':
 * @property string $id
 * @property string $dateTime
 * @property string $venue
 * @property integer $TotalSeats
 * @property integer $avilableSeat
 * @property integer $earlyBirdCost
 * @property string $earlyBirdPaymentLink
 * @property string $subType
 * @property string $paymentLink
 * @property string $storeItemId
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreItem $storeItem
 */
class StoreEvent extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeEvent';
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
			array('TotalSeats, avilableSeat, earlyBirdCost, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('venue, earlyBirdPaymentLink, paymentLink', 'length', 'max'=>300),
			array('subType', 'length', 'max'=>4),
			array('storeItemId', 'length', 'max'=>11),
			array('dateTime, createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dateTime, venue, TotalSeats, avilableSeat, earlyBirdCost, earlyBirdPaymentLink, subType, paymentLink, storeItemId, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'dateTime' => 'Date Time',
			'venue' => 'Venue',
			'TotalSeats' => 'Total Seats',
			'avilableSeat' => 'Avilable Seat',
			'earlyBirdCost' => 'Early Bird Cost',
			'earlyBirdPaymentLink' => 'Early Bird Payment Link',
			'subType' => 'Sub Type',
			'paymentLink' => 'Payment Link',
			'storeItemId' => 'Store Item',
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
		$criteria->compare('dateTime',$this->dateTime,true);
		$criteria->compare('venue',$this->venue,true);
		$criteria->compare('TotalSeats',$this->TotalSeats);
		$criteria->compare('avilableSeat',$this->avilableSeat);
		$criteria->compare('earlyBirdCost',$this->earlyBirdCost);
		$criteria->compare('earlyBirdPaymentLink',$this->earlyBirdPaymentLink,true);
		$criteria->compare('subType',$this->subType,true);
		$criteria->compare('paymentLink',$this->paymentLink,true);
		$criteria->compare('storeItemId',$this->storeItemId,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId);
		$criteria->compare('updateId',$this->updateId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	
	/**
	 * Returns query to get post full detail
	 */
	public static function getQuery($isManager = false)
	{
		
        $sql = "SELECT e.id";
        
		$sql .= ",IFNULL(i.title, '') as title";
		$sql .= ",IFNULL(i.coverImage, '') as coverImage";
		$sql .= ",IFNULL(i.cardImage, '') as cardImage";
		$sql .= ",IFNULL(i.actionButtonText, '') as actionButtonText";
		$sql .= ",IFNULL(i.description, '') as description";
		$sql .= ",IFNULL(i.costType, '') as costType";
		$sql .= ",IFNULL(i.costOnline, '') as costOnline";
		$sql .= ",IFNULL(i.costPoints, '') as costPoints";
		$sql .= ",IFNULL(i.termConditionsLink, '') as termConditionsLink";
// 		$sql .= ",IFNULL(i.type, '') as type";
		$sql .= ",IFNULL(i.thankYouText, '') as thankYouText";
		$sql .= ",IFNULL(i.postPurchaseInstructions, '') as postPurchaseInstructions";
		$sql .= ",IFNULL(i.startDate, '') as startDate";
		$sql .= ",IFNULL(i.endDate, '') as endDate";
		
// 		$sql .= ",IFNULL(i.isDisabled, '') as isDisabled";
// 		$sql .= ",IFNULL(i.disableReason, '') as disableReason";
// 		$sql .= ",IFNULL(i.createDate, '') as createDate";
// 		$sql .= ",IFNULL(i.updateDate, '') as updateDate";
// 		$sql .= ",IFNULL(i.createId, '') as createId";
// 		$sql .= ",IFNULL(i.updateId, '') as updateId";
        
		$sql .= ",IFNULL(e.dateTime, '') as 'dateTime'";
		$sql .= ",IFNULL(e.venue, '') as 'venue'";
		$sql .= ",IFNULL(e.TotalSeats, '') as 'TotalSeats'";
		$sql .= ",IFNULL(e.avilableSeat, '') as 'avilableSeat'";
		$sql .= ",IFNULL(e.earlyBirdPaymentLink, '') as 'earlyBirdPaymentLink'";
		$sql .= ",subType ";
		$sql .= ",IFNULL(e.paymentLink, '') as 'paymentLink'";
		$sql .= ",IFNULL(e.storeItemId, '') as 'storeItemId'";		
		$sql .= ",IFNULL(c.id, '') as 'CID'";
		
		$sql .= " FROM storeEvent e  ";
		$sql .= " INNER JOIN storeItem i on i.id = e.storeItemId and i.type = 'EVENT' ";
		
		return $sql;
	}
	
	public static function getStoreEvents($page=1, $status = 'upcomming', $options=array(), $recordCount=9 ){
		
		$pagestart = ($page-1) * $recordCount;
		
		$sql = self::getQuery();
		
// 		$sql .= ' LEFT JOIN storeItemCity c on c.storeItemId = i.id ';
		
		if($status != 'all'){
			$sql .= ' WHERE e.dateTime > now() and i.startDate < now() and i.endDate > now() ';
			
// 			$sql .= ' and (c.id is null) ';
			
			$sql .= ' and i.isDisabled = 0 ';
		}
		
		$sql .= ' ORDER BY e.dateTime ASC';
		
		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
		
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
		
	}
	
	

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreEvent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
