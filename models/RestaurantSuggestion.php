<?php

/**
 * This is the model class for table "restaurantSuggestion".
 *
 * The followings are the available columns in table 'restaurantSuggestion':
 * @property string $id
 * @property string $userId
 * @property string $restaurantName
 * @property string $address
 * @property double $latitude
 * @property double $longitude
 * @property string $phoneNo
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
class RestaurantSuggestion extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'restaurantSuggestion';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, restaurantName', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('userId, createId, updateId', 'length', 'max'=>10),
            array('restaurantName, address, disableReason', 'length', 'max'=>128),
            array('phoneNo', 'length', 'max'=>20),
            array('createDate, updateDate', 'safe'),
       		array('latitude, longitude', 'numerical'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, restaurantName, address, latitude, longitude, phoneNo, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'restaurantName' => 'Restaurant Name',
            'address' => 'Address',
       		'latitude' => 'Latitude',
       		'longitude' => 'Longitude',
            'phoneNo' => 'Phone No',
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
    public function search($type='all')
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('userId',$this->userId,true);
        $criteria->compare('restaurantName',$this->restaurantName,true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('latitude',$this->latitude);
        $criteria->compare('longitude',$this->longitude);        
        $criteria->compare('phoneNo',$this->phoneNo,true);
        $criteria->compare('isDisabled',$this->isDisabled);
        $criteria->compare('disableReason',$this->disableReason,true);
        $criteria->compare('createDate',$this->createDate,true);
        $criteria->compare('updateDate',$this->updateDate,true);
        $criteria->compare('createId',$this->createId,true);
        $criteria->compare('updateId',$this->updateId,true);

        

        if($type =='done')
        	$criteria->addCondition("t.isDisabled = 1");
        elseif($type =='pending')
        	$criteria->addCondition("t.isDisabled = 0");
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        		'sort'=>array(
        		  		'defaultOrder'=>'createDate DESC'
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
     * @return RestaurantSuggestion the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get restaurantSuggestion records
     */
    public static function getQuery()
    {
        $sql = 'SELECT rs.id';
        $sql .= ',rs.userId';
        $sql .= ',rs.restaurantName';
        $sql .= ',IFNULL(rs.address, "") as address';
        $sql .= ',IFNULL(rs.phoneNo, "") as phoneNo';
        $sql .= ' FROM restaurantSuggestion rs';
        
        return $sql;
    }
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public static function getByUserId($userId)
    {
        //fetch all restaurantSuggestion records of a specific user
        $sql = self::getQuery();
        $sql .= ' WHERE rs.isDisabled=0 AND rs.userId=' .$userId;
        
        $restaurantSuggestions = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $restaurantSuggestions;
    }
}
