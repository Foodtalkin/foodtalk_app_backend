<?php

/**
 * This is the model class for table "storeUserFields".
 *
 * The followings are the available columns in table 'storeUserFields':
 * @property string $id
 * @property string $fieldsName
 * @property integer $isDisabled
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreItemUserInfo[] $storeItemUserInfos
 */
class StoreUserFields extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeUserFields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fieldsName, createId', 'required'),
			array('isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('fieldsName', 'length', 'max'=>50),
			array('createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fieldsName, isDisabled, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'storeItemUserInfos' => array(self::HAS_MANY, 'StoreItemUserInfo', 'storeUserFieldsId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fieldsName' => 'Fields Name',
			'isDisabled' => 'Is Disabled',
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
		$criteria->compare('fieldsName',$this->fieldsName,true);
		$criteria->compare('isDisabled',$this->isDisabled);
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
		$sql = "SELECT id";	
		$sql .= ",IFNULL(fieldsName, '') as fieldsName";
		$sql .= " FROM storeUserFields ";
		return $sql;
	}
	
	public static function getFields(){
		
		$sql = self::getQuery();
		$sql .= ' WHERE isDisabled = 0 ';
		
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
	}
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreUserFields the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
