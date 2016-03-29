<?php

/**
 * This is the model class for table "userMentioned".
 *
 * The followings are the available columns in table 'userMentioned':
 * @property integer $id
 * @property integer $userId
 * @property string $userName
 * @property integer $postId
 * @property integer $commentId
 */
class UserMentioned extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'userMentioned';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, userName, postId', 'required'),
			array('userId, postId, commentId', 'numerical', 'integerOnly'=>true),
			array('userName', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userId, userName, postId, commentId', 'safe', 'on'=>'search'),
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
			'userId' => 'User',
			'userName' => 'User Name',
			'postId' => 'Post',
			'commentId' => 'Comment',
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
		$criteria->compare('userId',$this->userId);
		$criteria->compare('userName',$this->userName,true);
		$criteria->compare('postId',$this->postId);
		$criteria->compare('commentId',$this->commentId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getUserByComment($commentId){
		
			$sql = 'select userId, userName from userMentioned where commentId = '.$commentId;
			$users = Yii::app()->db->createCommand($sql)->queryAll(true);
			return $users;
		
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserMentioned the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
