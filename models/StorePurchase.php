<?php

/**
 * This is the model class for table "storePurchase".
 *
 * The followings are the available columns in table 'storePurchase':
 * @property string $id
 * @property string $storeItemId
 * @property string $userId
 * @property string $costType
 * @property integer $quantity
 * @property integer $costOnline
 * @property integer $costPoints
 * @property string $metaData
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreItem $storeItem
 * @property User $user
 */
class StorePurchase extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storePurchase';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storeItemId, userId, costType', 'required'),
			array('quantity, costOnline, costPoints, isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('storeItemId', 'length', 'max'=>11),
			array('userId', 'length', 'max'=>10),
			array('costType', 'length', 'max'=>6),
			array('disableReason', 'length', 'max'=>200),
			array('createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, storeItemId, userId, costType, quantity, costOnline, costPoints, metaData, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storeItemId' => 'Store Item',
			'userId' => 'User',
			'costType' => 'Cost Type',
			'quantity' => 'Quantity',
			'costOnline' => 'Cost Online',
			'costPoints' => 'Cost Points',
			'metaData' => 'Meta Data',
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
		$criteria->compare('storeItemId',$this->storeItemId,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('costType',$this->costType,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('costOnline',$this->costOnline);
		$criteria->compare('costPoints',$this->costPoints);
		$criteria->compare('metaData',$this->metaData,true);
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorePurchase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
