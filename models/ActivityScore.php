<?php

/**
 * This is the model class for table "activityScore".
 *
 * The followings are the available columns in table 'activityScore':
 * @property string $facebookId
 * @property integer $avilablePoints
 * @property double $totalPoints
 * @property integer $score
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 */
class ActivityScore extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activityScore';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('facebookId, createDate', 'required'),
			array('avilablePoints, score', 'numerical', 'integerOnly'=>true),
			array('totalPoints', 'numerical'),
			array('facebookId', 'length', 'max'=>30),
			array('createId, updateId', 'length', 'max'=>10),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('facebookId, avilablePoints, totalPoints, score, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'facebookId' => 'Facebook',
			'avilablePoints' => 'Avilable Points',
			'totalPoints' => 'Total Points',
			'score' => 'Score',
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

		$criteria->compare('facebookId',$this->facebookId,true);
		$criteria->compare('avilablePoints',$this->avilablePoints);
		$criteria->compare('totalPoints',$this->totalPoints);
		$criteria->compare('score',$this->score);
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
	 * @return ActivityScore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
