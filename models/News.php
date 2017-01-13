<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property string $id
 * @property string $title
 * @property string $coverImage
 * @property string $source
 * @property string $sourceUrl
 * @property string $description
 * @property string $startDate
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 */
class News extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, coverImage, description', 'required'),
			array('isDisabled', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('coverImage, sourceUrl', 'length', 'max'=>255),
			array('source', 'length', 'max'=>50),
			array('disableReason', 'length', 'max'=>128),
			array('createId, updateId', 'length', 'max'=>10),
			array('startDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, coverImage, source, sourceUrl, description, startDate, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'coverImage' => 'Cover Image',
			'source' => 'Source',
			'sourceUrl' => 'Source Url',
			'description' => 'Description',
			'startDate' => 'Start Date',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('coverImage',$this->coverImage,true);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('sourceUrl',$this->sourceUrl,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('startDate',$this->startDate,true);
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

	public static function getQuery($isManager = false)
	{
	
        $sql = "SELECT id";
        $sql .= ",IFNULL(title, '') as title";
        $sql .= ",IFNULL(coverImage, '') as coverImage";
        $sql .= ",IFNULL(source, '') as source";
        $sql .= ",IFNULL(sourceUrl, '') as sourceUrl";
        $sql .= ",IFNULL(description, '') as description";
        $sql .= ",IFNULL(startDate, '') as startDate";
        $sql .= ",IFNULL(isDisabled, '') as isDisabled";
        $sql .= " FROM news";
	
		return $sql;
	}
	
	public static function getnews($page=1, $status = 'upcomming', $options=array(), $recordCount=25 ){
	
		$pagestart = ($page-1) * $recordCount;
	
		$sql = self::getQuery();
	
		if($status != 'all'){
// 			$sql .= ' WHERE startDate < now() ';
			$sql .= ' WHERE isDisabled = 0 ';
		}
	
		$sql .= ' ORDER BY createDate DESC';
	
		$sql .= ' LIMIT '. $pagestart .', '. $recordCount;
	
		$result = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $result;
	
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
