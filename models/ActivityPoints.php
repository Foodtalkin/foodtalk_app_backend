<?php

/**
 * This is the model class for table "activityPoints".
 *
 * The followings are the available columns in table 'activityPoints':
 * @property integer $id
 * @property string $activityTable
 * @property string $activityTitle
 * @property string $platform
 * @property string $activityDesc
 * @property double $points
 * @property double $timefactor
 * @property double $minimum
 * @property double $maximum
 * @property double $penality
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property ActivityLog[] $activityLogs
 */
class ActivityPoints extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activityPoints';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('activityTable, activityTitle, platform, activityDesc, points, timefactor, minimum, maximum, penality', 'required'),
			array('isDisabled', 'numerical', 'integerOnly'=>true),
			array('points, timefactor, minimum, maximum, penality', 'numerical'),
			array('activityTable', 'length', 'max'=>50),
			array('activityTitle, disableReason', 'length', 'max'=>128),
			array('platform', 'length', 'max'=>8),
			array('createId, updateId', 'length', 'max'=>10),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, activityTable, activityTitle, platform, activityDesc, points, timefactor, minimum, maximum, penality, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'activityLogs' => array(self::HAS_MANY, 'ActivityLog', 'activityType'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'activityTable' => 'Activity Table',
			'activityTitle' => 'Activity Title',
			'platform' => 'Platform',
			'activityDesc' => 'Activity Desc',
			'points' => 'Points',
			'timefactor' => 'Timefactor',
			'minimum' => 'Minimum',
			'maximum' => 'Maximum',
			'penality' => 'Penality',
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
		$criteria->compare('activityTable',$this->activityTable,true);
		$criteria->compare('activityTitle',$this->activityTitle,true);
		$criteria->compare('platform',$this->platform,true);
		$criteria->compare('activityDesc',$this->activityDesc,true);
		$criteria->compare('points',$this->points);
		$criteria->compare('timefactor',$this->timefactor);
		$criteria->compare('minimum',$this->minimum);
		$criteria->compare('maximum',$this->maximum);
		$criteria->compare('penality',$this->penality);
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
	 * @return ActivityPoints the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
