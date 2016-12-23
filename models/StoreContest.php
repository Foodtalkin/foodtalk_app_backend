<?php

/**
 * This is the model class for table "storeContest".
 *
 * The followings are the available columns in table 'storeContest':
 * @property integer $id
 * @property string $winnerAnnouncementDate
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
class StoreContest extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeContest';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storeItemId, disableReason, createId, updateId', 'required'),
			array('isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('storeItemId', 'length', 'max'=>11),
			array('disableReason', 'length', 'max'=>200),
			array('winnerAnnouncementDate, createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, winnerAnnouncementDate, storeItemId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'winnerAnnouncementDate' => 'Winner Announcement Date',
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
		$criteria->compare('winnerAnnouncementDate',$this->winnerAnnouncementDate,true);
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreContest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
