<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property string $id
 * @property string $userId
 * @property string $postId
 * @property string $postUserId
 * @property string $comment
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Post $post
 * @property User $postUser
 */
class Comment extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, postId, postUserId, comment', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('userId, postId, postUserId, createId, updateId', 'length', 'max'=>10),
            array('comment', 'length', 'max'=>500),
            array('disableReason', 'length', 'max'=>128),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, postId, postUserId, comment, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'post' => array(self::BELONGS_TO, 'Post', 'postId'),
            'postUser' => array(self::BELONGS_TO, 'User', 'postUserId'),
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
            'postUserId' => 'Post User',
            'comment' => 'Comment',
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
    public function search($type = false, $admin = false, $options = array())
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $action = Yii::app()->controller->action->id;
        
        $criteria->compare('id',$this->id,true);
        $criteria->compare('userId',$this->userId,true);
        $criteria->compare('postId',$this->postId,true);
        $criteria->compare('postUserId',$this->postUserId,true);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('isDisabled',$this->isDisabled);
        $criteria->compare('disableReason',$this->disableReason,true);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        
        if($action=='disabled')
        	$criteria->addCondition("t.isDisabled = 1");
        else
        	$criteria->addCondition("t.isDisabled = 0");

//         if($action=='reported')
//         	$criteria->addCondition("t.isDisabled = 1");
        
        
        
//         if($admin){
//         	$criteria->with[]='user';
//         	$criteria->compare("user.userName",$this->userId);
//         }
//         if($admin){
//         	$criteria->with[]='user pu';
//         	$criteria->compare("pu userName usrName",$this->postUserId);
//         }
        
        if($action=='reported'){
        	$criteria->join =" Inner JOIN flag f ON f.commentId = t.id ";
        	$order = 'f.createDate desc';
        }
        
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        		'sort'=>array(
        				'defaultOrder'=>'t.createDate desc'
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
     * @return Comment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get all comments of a post and related users
     */
    public static function getQuery()
    {
        $sql = 'SELECT c.id';
        $sql .= ',c.comment';
        $sql .= ',c.createDate';
        $sql .= ',NOW() as currentDate';
        $sql .= ',u.id as userId';
        $sql .= ',IFNULL(u.userName, "") as userName';
        $sql .= ',IFNULL(u.fullName, "") as fullName';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as userImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as userThumb';
        $sql .= ' FROM comment c JOIN user u ON c.userId = u.id and c.isDisabled = 0 ';
        
        return $sql;
    }
    
    public static function getCommentsByPostId($postId)
    {
        $sql = self::getQuery();
        $sql .= ' WHERE c.postId = ' . $postId;
        $sql .= ' ORDER BY c.createDate ASC';
        
        $comments = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $comments;
    }
    
    public static function getLastComment($postId)
    {
        $sql = self::getQuery();
        $sql .= ' WHERE c.postId = ' . $postId;
        $sql .= ' ORDER BY c.createDate DESC';
        $sql .= ' LIMIT 1';

        $lastComment = Yii::app()->db->createCommand($sql)->queryRow(true);
        return $lastComment;
    }
}
