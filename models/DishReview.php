<?php

/**
 * This is the model class for table "dishReview".
 *
 * The followings are the available columns in table 'dishReview':
 * @property string $postId
 * @property string $dishId
 * @property double $rating
 * @property string $review
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property Dish $dish
 * @property Post $post
 */
class DishReview extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dishReview';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('postId, dishId', 'required'),
			array('createId, updateId', 'numerical', 'integerOnly'=>true),
			array('rating', 'numerical'),
			array('postId', 'length', 'max'=>11),
			array('dishId', 'length', 'max'=>10),
			array('review', 'length', 'max'=>255),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('postId, dishId, rating, review, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'dish' => array(self::BELONGS_TO, 'Dish', 'dishId'),
			'post' => array(self::BELONGS_TO, 'Post', 'postId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'postId' => 'Post',
			'dishId' => 'Dish',
			'rating' => 'Rating',
			'review' => 'Review',
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

		$criteria->compare('postId',$this->postId,true);
		$criteria->compare('dishId',$this->dishId,true);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('review',$this->review,true);
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
	 * @return DishReview the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public static function getDishNmae($name='')
	{
		//fetch tags and tagMap count by tagName
		$sql = 'SELECT t.id';
		$sql .= ',t.tagName';
		$sql .= ',COUNT(t.id) as postCount';
		$sql .= ' FROM tag t';
		$sql .= ' JOIN tagMap tm ON tm.tagId=t.id';
		$sql .= ' JOIN post p ON p.id=tm.postId';
		$sql .= ' WHERE p.checkedInRestaurantId IS NOT NULL';
	
		if(!empty($tagName))
			$sql .= ' AND t.tagName LIKE "' .$tagName. '%"';
	
		$sql .= ' GROUP BY t.tagName, t.id';
		$sql .= ' HAVING postCount > 0';
		$sql .= ' ORDER BY t.tagName, t.id ASC';
	
		$tags = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $tags;
	}
}
