<?php

/**
 * This is the model class for table "bookmark".
 *
 * The followings are the available columns in table 'bookmark':
 * @property string $id
 * @property string $userId
 * @property string $postId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property Post $post
 * @property User $user
 */
class Bookmark extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bookmark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, postId', 'required'),
			array('isDisabled', 'numerical', 'integerOnly'=>true),
			array('userId, postId, createId, updateId', 'length', 'max'=>10),
			array('disableReason', 'length', 'max'=>128),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userId, postId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'post' => array(self::BELONGS_TO, 'Post', 'postId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'postId' => 'Post',
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
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('postId',$this->postId,true);
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
	 * @return Bookmark the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getBookmarkDish($userId, $page=1, $recordCount=15, $exceptions='')
	{
		$pagestart = ($page-1) * $recordCount;

        //fetch all tips related to current restaurant
        $sql = 'select d.dishName, p.id, IFNULL(r.id, 0) as checkedInRestaurantId, IFNULL(r.restaurantName, "") as restaurantName, r.region FROM dish d INNER JOIN dishReview dr on d.id = dr.dishId INNER JOIN post p on p.id = dr.postId and p.isDisabled = 0';
		$sql .= ' INNER JOIN  bookmark b on b.postId = p.id and b.userId = '.$userId;
		$sql .= ' LEFT JOIN  restaurant r on r.id = p.checkedInRestaurantId and r.isActivated = 1 and r.isDisabled = 0';
		$sql .= ' WHERE d.isDisabled=0 and b.isDisabled = 0';

        if($exceptions)
            $sql .= ' AND d.id NOT IN (' .$exceptions. ')';
        
        $sql .= ' ORDER BY b.createDate desc ';
        

        $sql .= ' LIMIT '. $pagestart .', '. $recordCount;
        
//         echo $sql;

        $posts = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $posts;
	}
}
