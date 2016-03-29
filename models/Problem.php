<?php

/**
 * This is the model class for table "problem".
 *
 * The followings are the available columns in table 'problem':
 * @property string $id
 * @property string $userId
 * @property string $problemArea
 * @property string $details
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Problem extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'problem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, problemArea, details', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('userId, createId, updateId', 'length', 'max'=>10),
            array('problemArea, disableReason', 'length', 'max'=>128),
            array('details', 'length', 'max'=>1000),
            array('createDate, updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, problemArea, details, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'problemArea' => 'Problem Area',
            'details' => 'Details',
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
        $criteria->compare('problemArea',$this->problemArea,true);
        $criteria->compare('details',$this->details,true);
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
     * @return Problem the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get problem records
     */
    public static function getQuery()
    {
        $sql = 'SELECT p.id';
        $sql .= ',p.userId';
        $sql .= ',p.problemArea';
        $sql .= ',p.details';
        $sql .= ' FROM problem p';
        
        return $sql;
    }
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public static function getByUserId($userId)
    {
        //fetch all problem records of a specific user
        $sql = self::getQuery();
        $sql .= ' WHERE p.isDisabled=0 AND p.userId=' .$userId;
        
        $problems = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $problems;
    }
}
