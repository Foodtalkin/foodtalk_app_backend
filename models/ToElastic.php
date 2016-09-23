<?php

/**
 * This is the model class for table "toElastic".
 *
 * The followings are the available columns in table 'toElastic':
 * @property integer $id
 * @property integer $entityId
 * @property string $entity
 * @property string $status
 * @property integer $process
 * @property string $createDate
 * @property string $updateDate
 */
class ToElastic extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'toElastic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('entityId, entity, status, createDate', 'required'),
			array('entityId, process', 'numerical', 'integerOnly'=>true),
			array('entity', 'length', 'max'=>10),
			array('status', 'length', 'max'=>6),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, entityId, entity, status, process, createDate, updateDate', 'safe', 'on'=>'search'),
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
			'entity' => 'Entity',
			'status' => 'Status',
			'process' => 'Process',
			'createDate' => 'Create Date',
			'updateDate' => 'Update Date',
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
		$criteria->compare('entityId',$this->entityId);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('process',$this->process);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ToElastic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
