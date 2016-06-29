<?php

/**
 * This is the model class for table "dish".
 *
 * The followings are the available columns in table 'dish':
 * @property string $id
 * @property string $dishName
 * @property string $url
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 */
class Dish extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dish';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dishName', 'required'),
			array('isDisabled', 'numerical', 'integerOnly'=>true),
			array('dishName, url', 'length', 'max'=>191),
			array('disableReason', 'length', 'max'=>128),
			array('createId, updateId', 'length', 'max'=>10),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dishName, url, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
				'postCount' => array(self::STAT, 'DishReview', 'dishId'),
				'dishReview' => array(self::HAS_MANY, 'DishReview', 'dishId'),
				'dishCuisine' => array(self::HAS_MANY, 'DishCuisine', 'dishId'),
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dishName' => 'Dish Name',
			'url' => 'Url',
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
	public function search($type=false)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('dishName',$this->dishName,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('t.isDisabled',$this->isDisabled);
		$criteria->compare('disableReason',$this->disableReason,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId,true);
		$criteria->compare('updateId',$this->updateId,true);
		
		$order = 't.createDate desc';
		
// 		echo 'SHASH'.Yii::app()->controller->action;
		

		
		if(Yii::app()->controller instanceof CuisineController){
			$criteria->join .=" INNER JOIN dishCuisine dc ON dc.dishId = t.id  and  dc.cuisineId = ".Yii::app()->request->getParam('id',false);
		}
				
			
// 			$criteria->with[]='dishCuisine';			

// 		if($type=='dishCuisine'){
// 			$criteria->addCondition("cuisineId = 19");
// 			$criteria->addCondition("dishCuisine. = 1");
// 		}
		
		
		
		if($type=='disabled'){
			$criteria->addCondition("t.isDisabled = 1");
		}
		if($type=='active'){
			$criteria->addCondition("t.isDisabled = 0");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'sort'=>array(
						'defaultOrder'=>$order
				),
				'pagination'=>array(
						'pageSize'=>100
				)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dish the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getDishByNmae($dishName,$create = true){
		
		$dishName = trim($dishName);
		$dish = self::model()->findByAttributes(array('dishName'=>$dishName));
		
		if($create){
			if(!$dish){
				$append ='';
				$dish = new self('api_insert');
				$dish->dishName = $dishName;
				
				$doSave=true;
				while ($doSave){
					
					$dish->url = self::urlfy($dishName.$append);
					
					try {
						
						$dish->save();
						$doSave = false;
						
					}catch (Exception $e){

						$append++;
						
					}
				}
				
				
			}
		}
		
		return $dish;
		
	}
	
	public static function listAll($options=array('id'=>0))
	{
		$sql = 'SELECT d.dishName name, d.url, d.id , COUNT(d.id) as postCount';
		
		$sql .=' FROM `dish` d INNER JOIN dishReview r on r.dishId = d.id INNER JOIN post on post.id = r.postId and post.isDisabled = 0 ';
		
		if (isset($options['with']) && $options['with'] == 'checkin')
			$sql .=' INNER JOIN restaurant on post.checkedInRestaurantId = restaurant.id and post.checkinId is not null AND restaurant.isDisabled = 0 AND restaurant.isActivated = 1';
		
		$sql .=' where  d.isDisabled = 0 ';
		
		if( isset($options['id']) && is_numeric($options['id']) && $options['id']>0)
		$sql .=' and  d.id > '.$options['id'];
		
		$sql .=' GROUP BY d.dishName ORDER BY d.id ASC';
		
// 		echo $sql ;
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
		
	}
	
	
	
	public static function listByName($dishName='', $region='')
	{
		if(strlen($dishName) >1 )
		{
			$sql = 'SELECT d.id, CHAR_LENGTH(d.dishName) length, d.dishName , COUNT(d.id) postCount, MATCH(dishName) against ("'.$dishName.'") score FROM `dish` d inner JOIN dishReview r on r.dishId = d.id INNER join post on post.id = r.postId AND post.checkedInRestaurantId is not null and post.isDisabled = 0 ';
			$sql .= ' INNER JOIN restaurant on restaurant.id = post.checkedInRestaurantId AND restaurant.isDisabled = 0 AND restaurant.isActivated = 1';
			
			if(strlen($region) >1)
				$sql .=' and restaurant.region ="'.$region.'" ';
			
			$sql .=' where  MATCH(dishName) against ("'.$dishName.'*" IN BOOLEAN MODE ) AND d.isDisabled = 0 GROUP by d.id ORDER BY score desc, length asc, dishName ASC';
			
		}else{
			$sql = 'SELECT d.id, d.dishName , COUNT(d.id) postCount, MATCH(dishName) against ("'.$dishName.'") score FROM `dish` d inner JOIN dishReview r on r.dishId = d.id INNER join post on post.id = r.postId ';
			$sql .=' where  MATCH(dishName) against ("'.$dishName.'*" IN BOOLEAN MODE ) AND d.isDisabled = 0 GROUP by d.id ORDER BY score desc, dishName ASC';
			
		}
		//fetch tags and tagMap count by tagName
// 		$sql = 'SELECT t.id';
// 		$sql .= ',t.tagName ';
// 		$sql .= ',COUNT(t.id) as postCount';
// 		$sql .= ' FROM tag t';
// 		$sql .= ' JOIN tagMap tm ON tm.tagId=t.id';
// 		$sql .= ' JOIN post p ON p.id=tm.postId';
// 		$sql .= ' WHERE p.checkedInRestaurantId IS NOT NULL';
	
// 		if(!empty($tagName))
// 			$sql .= ' AND t.tagName LIKE "' .$tagName. '%"';
	
// 		$sql .= ' GROUP BY t.tagName, t.id';
// 		$sql .= ' HAVING postCount > 0';
// 		$sql .= ' ORDER BY t.tagName, t.id ASC';

// echo $sql;
		$tags = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $tags;
	}
}
