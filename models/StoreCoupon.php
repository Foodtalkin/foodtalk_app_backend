<?php

/**
 * This is the model class for table "storeCoupon".
 *
 * The followings are the available columns in table 'storeCoupon':
 * @property string $id
 * @property string $storeOfferId
 * @property string $code
 * @property integer $userId
 * @property integer $isUsed
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property integer $createId
 * @property integer $updateId
 *
 * The followings are the available model relations:
 * @property StoreOffer $storeOffer
 */
class StoreCoupon extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storeCoupon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storeOfferId, code', 'required'),
			array('userId, isUsed, isDisabled, createId, updateId', 'numerical', 'integerOnly'=>true),
			array('storeOfferId', 'length', 'max'=>11),
			array('code', 'length', 'max'=>100),
			array('disableReason', 'length', 'max'=>200),
			array('createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, storeOfferId, code, userId, isUsed, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'storeOffer' => array(self::BELONGS_TO, 'StoreOffer', 'storeOfferId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storeOfferId' => 'Store Offer',
			'code' => 'Code',
			'userId' => 'User',
			'isUsed' => 'Is Used',
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
		$criteria->compare('storeOfferId',$this->storeOfferId,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('isUsed',$this->isUsed);
		$criteria->compare('isDisabled',$this->isDisabled);
		$criteria->compare('disableReason',$this->disableReason,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId);
		$criteria->compare('updateId',$this->updateId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getNewCoupon($storeOfferId,$userId, $generate=false){
	
		start:
		
		if($generate){
			try {
				$coupon = new self('create_api');
				$coupon->code = randomStringAlphaNum(6);
				$coupon->userId = $userId;
				$coupon->storeOfferId = $storeOfferId;
				$coupon->save();
			}
			catch (Exception $e){
				if($e->getCode()==23000)
					goto start;
				else
					return false;
			}
				
// 			if ($coupon->hasErrors()){
				
// 				$code = randomStringAlphaNum(6);
// 				goto start;
// 				throw new Exception(print_r($coupon->getErrors(), true), WS_ERR_UNKNOWN);
// 			}
		}
		else{
			$coupon = self::model()->findByAttributes(array('storeOfferId'=>$storeOfferId, 'userId'=>null ));
		    if(empty($coupon)){
		    	return false;
	//     		throw new Exception(print_r('Invalid event id', true), WS_ERR_WONG_VALUE);
	    	}
			$coupon->userId = $userId;
			$coupon->save();
		}
		return $coupon;
	
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreCoupon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
