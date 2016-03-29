<?php

/**
 * This is the model class for table "favourite".
 *
 * The followings are the available columns in table 'favourite':
 * @property string $id
 * @property string $userId
 * @property string $restaurantId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Restaurant $restaurant
 */
class Favourite extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'favourite';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, restaurantId', 'required'),
            array('isDisabled', 'numerical', 'integerOnly'=>true),
            array('userId, restaurantId, createId, updateId', 'length', 'max'=>10),
            array('disableReason', 'length', 'max'=>128),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, restaurantId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'restaurant' => array(self::BELONGS_TO, 'Restaurant', 'restaurantId'),
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
            'restaurantId' => 'Restaurant',
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
        $criteria->compare('restaurantId',$this->restaurantId,true);
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
     * @return Favourite the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get all favourite records
     */
    public static function getQuery()
    {
        $sql = "SELECT f.id";
        $sql .= ",f.userId";
        $sql .= ",f.restaurantId";
        $sql .= ',IFNULL(r.restaurantName, "") as restaurantName';
        $sql .= ',IFNULL(r.phone1, "") as phone1';
        $sql .= ',IFNULL(r.phone2, "") as phone2';
        $sql .= ',IFNULL(CONCAT("' . imagePath('restaurant') . '", r.image), "") as restaurantImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('restaurant') . '", r.image), "") as restaurantThumb';
        $sql .= " FROM favourite f JOIN restaurant r ON f.restaurantId = r.id";
        
        return $sql;
    }
    
    /**
     * Returns favourite records by userId
     */
    public static function getFavouriteRestaurants($selectedUserId)
    {
        $sql = self::getQuery();
        $sql .= ' WHERE f.userId = ' . $selectedUserId;
        $sql .= ' ORDER BY f.createDate DESC';
        
        $favourites = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $favourites;
    }
}
