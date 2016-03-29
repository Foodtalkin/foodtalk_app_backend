<?php

/**
 * This is the model class for table "restaurantReport".
 *
 * The followings are the available columns in table 'restaurantReport':
 * @property string $id
 * @property string $userId
 * @property string $restaurantId
 * @property integer $reportType
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Restaurant $restaurant
 */
class RestaurantReport extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'restaurantReport';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, restaurantId, reportType', 'required'),
            array('reportType', 'numerical', 'integerOnly'=>true),
            array('userId, restaurantId, createId, updateId', 'length', 'max'=>10),
            array('createDate, updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, restaurantId, reportType, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'reportType' => 'Report Type',
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
        $criteria->compare('reportType',$this->reportType);
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
     * @return RestaurantReport the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get all restaurantReport records
     */
    public static function getQuery()
    {
        $sql = "SELECT rr.id";
        $sql .= ",rr.userId";
        $sql .= ",rr.restaurantId";
        $sql .= ',IFNULL(r.restaurantName, "") as restaurantName';
        $sql .= ',IFNULL(CONCAT("' . imagePath('restaurant') . '", r.image), "") as restaurantImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('restaurant') . '", r.image), "") as restaurantThumb';
        $sql .= ',IFNULL(u.userName, "") as userName';
        $sql .= ',IFNULL(u.email, "") as email';
        $sql .= ',IFNULL(CONCAT("' . imagePath('user') . '", u.image), "") as userImage';
        $sql .= ',IFNULL(CONCAT("' . thumbPath('user') . '", u.image), "") as userThumb';
        $sql .= " FROM restaurantReport rr JOIN restaurant r ON rr.restaurantId = r.id";
        $sql .= " JOIN user u ON rr.userId = u.id";
        
        return $sql;
    }
    
    /**
     * Returns restaurantReport records by userId
     */
    public static function getByUserId($selectedUserId, $reportType=0)
    {
        $sql = self::getQuery();
        $sql .= ' WHERE rr.userId = ' . $selectedUserId;
        
        if($reportType)
            $sql .= ' AND rr.reportType=' . $reportType;
        
        $sql .= ' ORDER BY rr.createDate DESC';
        
        $restaurantReports = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $restaurantReports;
    }
    
    /**
     * Returns restaurantReport records by restaurantId
     */
    public static function getByRestaurantId($restaurantId, $reportType=0)
    {
        $sql = self::getQuery();
        $sql .= ' WHERE rr.restaurantId = ' . $restaurantId;
        
        if($reportType)
            $sql .= ' AND rr.reportType=' . $reportType;
        
        $sql .= ' ORDER BY rr.createDate DESC';
        
        $restaurantReports = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $restaurantReports;
    }
}
