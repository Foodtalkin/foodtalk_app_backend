<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property string $id
 * @property string $userId
 * @property string $restaurantId
 * @property string $createDate
 * @property string $createId
 * @property string $updateId
 */
class Checkins extends FoodTalkActiveRecord {
	/**
	 *
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'checkins';
	}
	/**
	 *
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array (
				array (
						'userId, restaurantId',
						'required' 
				),
				array (
						'userId, restaurantId',
						'length',
						'max' => 10 
				),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array (
						'id, userId, restaurantId, createDate, createId, updateId',
						'safe',
						'on' => 'search' 
				) 
		)
		;
	}
	
	public function behaviors()
	{
// 		remove timestamp capture for created date
		return array();
	}
	
	public function getCheckin($userId, $restaurantId, $checkinDate = 'now()', $forceCheckin = true) {
		
		if ($checkinDate != 'now()') {
			$date = new DateTime ( $checkinDate );
			$dateStr = "'" . $date->format ( 'Y-m-d H:i:s' ) . "'";
		} else {
			$dateStr = "NOW()";
		}
		
		$checkin = self::model ()->findByAttributes ( array (
				'userId' => $userId,
				'restaurantId' => $restaurantId 
		), "DATE_FORMAT(createDate,'%m-%d-%Y') = DATE_FORMAT(" . $dateStr . ",'%m-%d-%Y')" );
		
		if (! $checkin && $forceCheckin) {
			
			$checkin = new Checkins ();
			$checkin->userId = filter_var ( $userId, FILTER_SANITIZE_NUMBER_INT );
			if ($checkinDate != 'now()')
				$checkin->createDate = $date->format ( 'Y-m-d H:i:s' );
			
			$checkin->restaurantId = filter_var ( $restaurantId, FILTER_SANITIZE_NUMBER_INT );
			$checkin->save ();
			
			if ($checkin->hasErrors ()) {
				throw new Exception ( print_r ( $checkin->getErrors (), true ), WS_ERR_UNKNOWN );
			}
		}
		return $checkin;
	}
	
	public function clean($entity, $action = 'disabled') {
		
		if ($action == 'delete')
			$action = 'disabled';
		
		$entityName = get_class ( $entity );
		// self::model()->update_all(array(
		// 'set' => array('status' => 0),
		// 'conditions' => array('id' => array(1,2,3,4))
		// ));
		switch ($entityName) {
			case 'User' :
				// detete all checkins by user
				$sql = "delete from checkins where userId = " . $entity->id;
				Yii::app ()->db->createCommand ( $sql )->query ();
				break;
			case 'Post' :
				if ($action == 'disabled') {
					// delete checkin if no avtive post are attached to it
					if(!$entity->checkinId){
						return true;
					}
					
					$sql = "DELETE checkins FROM checkins
					INNER JOIN (SELECT IF(count(1) < 1 , 'yes', 'no')  doDelete, checkins.id FROM `checkins`, post WHERE post.checkinId = checkins.id and post.checkinId = " . $entity->checkinId . " and post.isDisabled=0 ) as a on checkins.id = a.id
					WHERE a.doDelete = 'yes'";
					Yii::app ()->db->createCommand ( $sql )->query ();
				}
				if ($action == 'enabled') {
					// remove checkin
					if(!$entity->checkedInRestaurantId){
						return true;
					}
					
					$checkin = self::getCheckin ( $entity->userId, $entity->checkedInRestaurantId, $entity->createDate );
					$oldCheckinId = $entity->checkinId;
					$entity->checkinId = $checkin->id;
					
					if ($entity->save () && is_numeric($oldCheckinId) && $oldCheckinId > 0) {
						// delete old checkin if no avtive post are attached to it
						$sql = "DELETE checkins FROM checkins
						INNER JOIN (SELECT IF(count(1) < 1 , 'yes', 'no')  doDelete, checkins.id FROM `checkins`, post WHERE post.checkinId = checkins.id and post.checkinId = $oldCheckinId and post.isDisabled=0 ) as a on checkins.id = a.id
						WHERE a.doDelete = 'yes'";
						Yii::app ()->db->createCommand ( $sql )->query ();
					}
				}
				if ($action == 'changerestaurant') {
					
					if(!$entity->checkedInRestaurantId){
						return true;
					}
					
					$checkin = self::getCheckin ( $entity->userId, $entity->checkedInRestaurantId, $entity->createDate );
					$oldCheckinId = $entity->checkinId;
					$entity->checkinId = $checkin->id;
					if ($entity->save () && is_numeric($oldCheckinId) && $oldCheckinId > 0) {
						// delete old checkin if no avtive post are attached to it
						$sql = "DELETE checkins FROM checkins
						INNER JOIN (SELECT IF(count(1) < 1 , 'yes', 'no')  doDelete, checkins.id FROM `checkins`, post WHERE post.checkinId = checkins.id and post.checkinId = $oldCheckinId and post.isDisabled=0 ) as a on checkins.id = a.id
						WHERE a.doDelete = 'yes'";
						Yii::app ()->db->createCommand ( $sql )->query ();
					}
				}
				break;
			case 'Restaurant' :
				if ($action == 'duplicate') {
					// moove all post to new RestaurantId
					$sql = 'update post set checkedInRestaurantId = ' . $entity->duplicateId . ' where checkedInRestaurantId = ' . $entity->id;
					Yii::app ()->db->createCommand ( $sql )->query ();
					// update all checkin with new RestaurantID
					$sql = 'update checkins set restaurantId = ' . $entity->duplicateId . ' where restaurantId = ' . $entity->id;
					Yii::app ()->db->createCommand ( $sql )->query ();
				}
				
				break;
			default :
				return false;
		}
		return true;
	}
	
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array ();
	}
	
	/**
	 *
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array (
				'id' => 'ID',
				'userId' => 'user Id',
				'restaurantId' => 'Restaurant Id',
				'createDate' => 'Create Date' 
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
	 *         based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria = new CDbCriteria ();
		
		$criteria->compare ( 'id', $this->id, true );
		$criteria->compare ( 'userId', $this->userId, true );
		$criteria->compare ( 'restaurantId', $this->restaurantId );
		$criteria->compare ( 'createDate', $this->createDate, true );
		
		return new CActiveDataProvider ( $this, array (
				'criteria' => $criteria 
		) );
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * 
	 * @param string $className
	 *        	active record class name.
	 * @return City the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
}
