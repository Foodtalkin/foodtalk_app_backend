<?php

/**
 * This is the model class for table "storeGoods".
 *
 * The followings are the available columns in table 'storeGoods':
 * @property integer $id
 * @property integer $totalQuantity
 * @property integer $availableQuantity
 * @property integer $limitPerUser
 * @property string $storeItemId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreItem $storeItem
 */
class StoreGoods extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeGoods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('totalQuantity, availableQuantity, limitPerUser, storeItemId, disableReason, createId, updateId', 'required'),
			array('totalQuantity, availableQuantity, limitPerUser, isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('storeItemId', 'length', 'max'=>11),
			array('disableReason', 'length', 'max'=>200),
			array('createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, totalQuantity, availableQuantity, limitPerUser, storeItemId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'totalQuantity' => 'Total Quantity',
			'availableQuantity' => 'Available Quantity',
			'limitPerUser' => 'Limit Per User',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('totalQuantity',$this->totalQuantity);
		$criteria->compare('availableQuantity',$this->availableQuantity);
		$criteria->compare('limitPerUser',$this->limitPerUser);
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

	public static function getQuery($isManager = false)
	{
	
		$sql = "SELECT g.id";
	
		$sql .= ",IFNULL(i.title, '') as title";
		$sql .= ",IFNULL(i.coverImage, '') as coverImage";
		$sql .= ",IFNULL(i.cardImage, '') as cardImage";
		$sql .= ",IFNULL(i.actionButtonText, '') as actionButtonText";
		$sql .= ",IFNULL(i.description, '') as description";
		$sql .= ",IFNULL(i.costType, '') as costType";
		$sql .= ",IFNULL(i.costOnline, '') as costOnline";
		$sql .= ",IFNULL(i.costPoints, '') as costPoints";
		$sql .= ",IFNULL(i.termConditionsLink, '') as termConditionsLink";
		$sql .= ",IFNULL(i.thankYouText, '') as thankYouText";
		$sql .= ",IFNULL(i.postPurchaseInstructions, '') as postPurchaseInstructions";
		$sql .= ",IFNULL(i.startDate, '') as startDate";
		$sql .= ",IFNULL(i.endDate, '') as endDate";
	
	
		$sql .= ",IFNULL(g.totalQuantity, '') as 'totalQuantity'";
		$sql .= ",IFNULL(g.availableQuantity, '') as 'availableQuantity'";
		$sql .= ",IFNULL(g.limitPerUser, '') as 'limitPerUser'";
		$sql .= ",IFNULL(g.storeItemId, '') as 'storeItemId'";
	
		$sql .= " FROM storeGoods g ";
		$sql .= " INNER JOIN storeItem i on i.id = g.storeItemId and i.type = 'GOODS' ";
	
		return $sql;
	}
	
	public static function getStoreGoods($page=1, $status = 'active', $options=array(), $recordCount=9 ){
	
		$pagestart = ($page-1) * $recordCount;
	
		$sql = self::getQuery();
	
		if($status != 'all'){
			$sql .= ' WHERE i.startDate < now() and i.endDate > now() ';
			$sql .= ' and i.isDisabled = 0 ';
		}
	
		$sql .= ' ORDER BY g.createDate ASC';
	
		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
	
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
	
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
