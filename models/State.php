<?php

/**
 * This is the model class for table "state".
 *
 * The followings are the available columns in table 'state':
 * @property string $id
 * @property string $stateName
 * @property string $countryId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property City[] $cities
 * @property Country $country
 */
class State extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'state';
	}

	
	public static function getStt(array $param, $create = true){
	
		if(!array_key_exists('name', $param))
			return false;
	
		$condition = ' ( stateName = :stateName or shortName = :shortName ) and countryId = :countryId ';
		$sqlParam = array(':stateName' => $param['name'], ':shortName' => $param['shortName'], ':countryId' => $param['countryId']);		
		$state = self::model()->find($condition, $sqlParam );
		
		if($create and array_key_exists('name', $param)){
			if(!$state){
				$state = new self('api_insert');
				$state->stateName = $param['name'];
				$state->shortName = $param['shortName'];
				$state->countryId = $param['countryId'];
				$state->save();
	
				if ($state->hasErrors())
				{
					throw new Exception(print_r($state->getErrors(), true), WS_ERR_UNKNOWN);
				}
			}
		}
		return $state;
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stateName, countryId', 'required'),
			array('isDisabled', 'numerical', 'integerOnly'=>true),
			array('stateName, disableReason', 'length', 'max'=>255),
			array('countryId', 'length', 'max'=>3),
			array('createId, updateId', 'length', 'max'=>10),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, stateName, countryId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
		);
	}

	
// 	public static function getState(array $param, $create = true){
	
// 		$state = self::model()->findByAttributes(array('stateName'=>$param['name'], 'countryId'=> $param['countryId']));
		
// 		if($create and array_key_exists('name', $param)){
// 			if(!$state){
// 				$state = new self('api_insert');
// 				$state->country = Country::getCountry(array('id'=>$param['countryId'], 'name'=>$param['countryName']));
// 				$state->countryName = $param['name'];
// 				$state->save();
// 			}
// 		}
// 		return $state;
// 	}
	
	
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'cities' => array(self::HAS_MANY, 'City', 'stateId'),
			'country' => array(self::BELONGS_TO, 'Country', 'countryId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'stateName' => 'State Name',
			'countryId' => 'Country',
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
		$criteria->compare('stateName',$this->stateName,true);
		$criteria->compare('countryId',$this->countryId,true);
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
	 * @return State the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
