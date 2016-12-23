<?php

/**
 * This is the model class for table "storeItemCity".
 *
 * The followings are the available columns in table 'storeItemCity':
 * @property string $id
 * @property string $storeItemId
 * @property string $cityId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property City $city
 * @property StoreItem $storeItem
 */
class StoreItemCity extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeItemCity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storeItemId, cityId', 'required'),
			array('isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('storeItemId', 'length', 'max'=>11),
			array('cityId', 'length', 'max'=>10),
			array('disableReason', 'length', 'max'=>200),
			array('createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, storeItemId, cityId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'storeItemId' => 'Store Item',
			'cityId' => 'City',
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
		$criteria->compare('cityId',$this->cityId,true);
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

	public static function getQuery()
	{
	
		$sql = "SELECT c.id as cityId";
	
		$sql .= ",IFNULL(c.cityName, '') as cityName";
		$sql .= ",IFNULL(c.stateId, '') as stateId";
		$sql .= ",IFNULL(c.countryId, '') as countryId";
		$sql .= ",IFNULL(c.regionId, '') as regionId";

		$sql .= " FROM storeItemCity i ";
		$sql .= " INNER JOIN city c on i.cityId = c.id ";
	
		return $sql;
	}
	
	
	public static function  getCities($itemId, $options=array())
	{
		$sql = self::getQuery();
		
		$sql .= ' WHERE i.storeItemId = '.$itemId;
		$sql .= ' and c.isDisabled = 0 ';
		
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
		
	}
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreItemCity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
