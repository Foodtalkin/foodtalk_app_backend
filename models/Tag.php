<?php

/**
 * This is the model class for table "tag".
 *
 * The followings are the available columns in table 'tag':
 * @property string $id
 * @property string $tagName
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property TagMap[] $tagMaps
 */
class Tag extends FoodTalkActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tag';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tagName', 'length', 'max'=>50),
            array('createId, updateId', 'length', 'max'=>10),
            array('updateDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, tagName, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
            'tagMaps' => array(self::HAS_MANY, 'TagMap', 'tagId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'tagName' => 'Tag Name',
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
        $criteria->compare('tagName',$this->tagName,true);
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
     * @return Tag the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Returns query to get tags and tagMap count
     */
    public static function getQuery()
    {
        $sql = 'SELECT t.id';
        $sql .= ',t.tagName';
        $sql .= ',(SELECT COUNT(*) FROM `tagMap` tm WHERE tm.tagId=t.id) as postCount';
        $sql .= ' FROM tag t';
        
        return $sql;
    }
    
    public static function getTagsByName1($tagName='')
    {
        //fetch tags and tagMap count by tagName
        $sql = self::getQuery();
        
        if(!empty($tagName))
            $sql .= ' WHERE t.tagName LIKE "' .$tagName. '%"';
        
        $sql .= ' ORDER BY t.tagName ASC';
        
        $tags = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $tags;
    }
    
    public static function getTagsByName($tagName='')
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
    
    public static function getTagsByPostId($postId)
    {
        //fetch tags and tagMap count by tagName
        $sql = 'SELECT DISTINCT t.id';
        $sql .= ',t.tagName';
        $sql .= ' FROM tag t';
        $sql .= ' JOIN tagMap tm ON tm.tagId=t.id';
        $sql .= ' WHERE tm.postId=' .$postId;
        
        $tags = Yii::app()->db->createCommand($sql)->queryAll(true);
        return $tags;
    }
}
