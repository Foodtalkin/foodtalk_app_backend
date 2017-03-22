<?php

/**
 * This is the model class for table "ads".
 *
 * The followings are the available columns in table 'ads':
 * @property string $id
 * @property string $title
 * @property integer $adType
 * @property integer $entityId
 * @property string $actionButtonText
 * @property string $description
 * @property string $startDate
 * @property string $expiry
 * @property double $latitude
 * @property double $longitude
 * @property integer $priority
 * @property integer $position
 * @property string $metaData
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property AdFilters[] $adFilters
 * @property AdType $adType0
 */
class Ads extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, adType, entityId, description', 'required'),
			array('adType, entityId, priority, position, isDisabled', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('title', 'length', 'max'=>200),
			array('actionButtonText', 'length', 'max'=>50),
			array('disableReason', 'length', 'max'=>128),
			array('createId, updateId', 'length', 'max'=>10),
			array('startDate, expiry, metaData, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, adType, entityId, actionButtonText, description, startDate, expiry, priority, position, metaData, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'adFilters' => array(self::HAS_MANY, 'AdFilters', 'adId'),
			'adType0' => array(self::BELONGS_TO, 'AdType', 'adType'),
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'adType' => 'Ad Type',
			'entityId' => 'Entity',
			'actionButtonText' => 'Action Button Text',
			'description' => 'Description',
			'startDate' => 'Start Date',
			'expiry' => 'Expiry',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'priority' => 'Priority',
			'position' => 'Position',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('adType',$this->adType,true);
		$criteria->compare('entityId',$this->entityId);
		$criteria->compare('actionButtonText',$this->actionButtonText,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('expiry',$this->expiry,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('position',$this->position);
		$criteria->compare('metaData',$this->metaData,true);
// 		$criteria->compare('isDisabled',$this->isDisabled, true);
		$criteria->compare('disableReason',$this->disableReason,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId,true);
		$criteria->compare('updateId',$this->updateId,true);

		
		
		
		switch (Yii::app()->request->getParam('status'))
		{
			case 'active':
				$criteria->addCondition("isDisabled = 0");
				break;
				
			case 'inactive':
				$criteria->addCondition("isDisabled = 1");				
				break;
				
			case 'upcomming':
				$criteria->addCondition("startDate > now()");
				break;
				
			case 'expired':
				$criteria->addCondition("expiry < now()");
				break;
				
			case 'default':
// 				break;
		}
		
// 		$criteria->addCondition("isDisabled = 1");
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getQuery($userId, $options=[])
	{
		$sql = 'SELECT a.id';
		$sql .= ',a.title';
		$sql .= ',IFNULL(t.tableName, "") as type';
		$sql .= ',IFNULL(a.entityId, "") as entityId';
		
		$sql .= ',IFNULL(a.actionButtonText, "") as actionButtonText';
		$sql .= ',IFNULL(a.description, "") as description';

		$sql .= ',IFNULL(a.startDate, "") as startDate';
		$sql .= ',IFNULL(a.expiry, "") as expiry';
		$sql .= ',IFNULL(a.cap, "") as cap';
		$sql .= ',IFNULL(a.priority, "") as priority';
		$sql .= ',IFNULL(a.position, "") as position';
		$sql .= ',IFNULL(a.metaData, "") as metaData';
		
		if(isset($options['latitude']) and isset($options['longitude']) ){

			$latitude = $options['latitude'];
			$longitude = $options['longitude'];
			$sql .= ", IF(a.latitude is null, 'N/A', DEGREES(ACOS(SIN(RADIANS($latitude)) * SIN(RADIANS(a.latitude)) + COS(RADIANS($latitude)) * COS(RADIANS(a.latitude)) * COS(RADIANS($longitude - a.longitude)))) * 111189.3006 ) as distance ";
		}
		
		$sql .= ' FROM ads as a';
		$sql .= ' INNER JOIN adType as t on t.id = a.adType ';
		
		return $sql;
	}
	
	public static function getAds($userId, $options=[])
	{
		$filters = AdFiltersFields::model()->findAllByAttributes(array('isDisabled'=>'0'));
		
		$user = User::model()->findByPk($userId);
		
		$sql = self::getQuery($userId, $options);
		
		$andWhere = [];
		
		foreach ($filters as $fil){
			
			$sql .= ' LEFT JOIN adFilters as '.$fil->name.' on '.$fil->name.'.adId = a.id AND '.$fil->name.'.name = "'.$fil->name.'" ';
			$andWhere[] = '( '.$fil->name.'.value '.$fil->operator.' "'.$user[$fil->userTableField].'" or '.$fil->name.'.value is null )';
		}
		
		$sql .= 'WHERE '. implode(' AND ', $andWhere);
		
		echo $sql;
		
// 		die("     :  DEAD");
		$users = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $users;
	}

	
	
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ads the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
