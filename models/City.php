<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property string $id
 * @property string $cityName
 * @property string $shortName
 * @property string $postalCode
 * @property string $stateId
 * @property string $countryId
 * @property integer $regionId
 * @property string $googleReference
 * @property string $googlePlaceId
 * @property integer $isDisabled
 * @property string $disableReason
 * @property string $createDate
 * @property string $updateDate
 * @property string $createId
 * @property string $updateId
 *
 * The followings are the available model relations:
 * @property Region $region
 * @property Country $country
 * @property State $state
 */
class City extends FoodTalkActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'city';
	}	
	
	public static function getCities($searchText = '')
	{
		$sql = 'select city.id, cityName, ifnull(s.stateName, "") as stateName, c.countryName, c.id as countryId from city INNER JOIN country c on city.countryId = c.id left join state s on city.stateId = s.id WHERE city.isDisabled = 0 ';
		
		if(!empty($searchText))
			$sql .= 'and city.cityName LIKE "%' .$searchText. '%"';
		else 
			$sql .= ' LIMIT 20';
		
		$restaurants = Yii::app()->db->createCommand($sql)->queryAll(true);
		return $restaurants;
	}
	
	public static function getAddressFromGoogle($google_place_id, $create = true){
		
		return self::getCityFromGoogle($google_place_id, $create, true);
		
	}
	
	
	public static function getCityFromGoogle($google_place_id, $create = true, $getaddress = false){
		
		$city = self::model()->findByAttributes(array('googlePlaceId'=>$google_place_id));

		if($city and $getaddress){
			
			$placeJson = file_get_contents( 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' .$google_place_id. '&key=AIzaSyB89N6mbHGzWBS1O47SzTch76_rN9K1Uws' );
			$place = json_decode( $placeJson);
				
		}		
		if(!$city){
			
			$placeJson = file_get_contents( 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' .$google_place_id. '&key=AIzaSyB89N6mbHGzWBS1O47SzTch76_rN9K1Uws' );
			$place = json_decode( $placeJson);
				
			
			$locality = false;	//city
			$postal_code = false;
			$country = false;
			$administrative_area_level_1 = false;
			$administrative_area_level_2 = false;
			$administrative_area_level_3 = false; //city
			
			foreach ( $place->result->address_components as $component ){
				if(in_array('locality', $component->types)){
					$locality['long_name'] = $component->long_name;
					$locality['short_name'] = $component->short_name;
				}
				if(in_array('country', $component->types)){
					$country['long_name'] = $component->long_name;
					$country['short_name'] = $component->short_name;
				}
				if(in_array('postal_code', $component->types)){
					$postal_code['long_name'] = $component->long_name;
				}
				if(in_array('administrative_area_level_1', $component->types)){
					$administrative_area_level_1['long_name'] = $component->long_name;
					$administrative_area_level_1['short_name'] = $component->short_name;
				}
				if(in_array('administrative_area_level_2', $component->types)){
					$administrative_area_level_2['long_name'] = $component->long_name;
					$administrative_area_level_2['short_name'] = $component->short_name;
				}
				if(in_array('administrative_area_level_3', $component->types) and !$locality ){
					$locality['long_name'] = $component->long_name;
					$locality['short_name'] = $component->short_name;
				}
			}
// 			if(in_array('political', $place->result->types)){
// 				if(in_array('locality', $place->result->types)){ 
// 					//  contains city
// 				}elseif(in_array('country', $place->result->types)) { 
// 					// contains country
// 				}
// 				else { 
// 					// address
// 				}
// 			}
			$sqlParam = array(':cityName' => $locality['long_name'], ':shortName' => $locality['short_name'], ':countryId' => $country['short_name']);
			
			if(!$administrative_area_level_1){
				
				$stat = State::getStt(array('shortName'=>$administrative_area_level_1['short_name'], 'name'=> $administrative_area_level_1['long_name'], 'countryId'=>$country['short_name']), false);
				if($stat){
					$condition = ' ( cityName = :cityName or shortName = :shortName ) and countryId = :countryId and stateId = :stateId ';
					$sqlParam[':stateId'] = $stat->id; 
					$city = self::model()->find($condition, $sqlParam );
				}
			}
			else{
				$condition = ' ( cityName = :cityName or shortName = :shortName ) and countryId = :countryId and stateId is null ';
				$city = self::model()->find($condition, $sqlParam );
			}
			
			if(!$city and $create){
				
				$city = new City('create_api');
					
				$city->cityName = $locality['long_name'];
				$city->shortName = $locality['short_name'];
				$city->googlePlaceId = $place->result->place_id;
				$city->googleReference = $place->result->reference;
				$city->region = 1;
				
				$ctry = Country::getCtry(array('id'=>$country['short_name'], 'name'=> $country['long_name']));
				$city->countryId = $ctry->id;
	
				if ($administrative_area_level_1){
					$state = State::getStt(array('shortName'=>$administrative_area_level_1['short_name'], 'name'=> $administrative_area_level_1['long_name'], 'countryId'=>$ctry->id));
					$city->stateId = $state->id;
				}
				
				$city->save();
				if ($city->hasErrors())
				{
					throw new Exception(print_r($city->getErrors(), true), WS_ERR_UNKNOWN);
				}
			}
		}
		
		if($getaddress){
			return array('city'=>$city, 'address'=>$place->result->formatted_address, 'place'=>$place);
		}
		
		return $city;
	}
	
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cityName, countryId', 'required'),
			array('regionId, isDisabled', 'numerical', 'integerOnly'=>true),
			array('cityName, disableReason', 'length', 'max'=>255),
			array('shortName, postalCode, regionId', 'length', 'max'=>255),
			array('stateId, createId, updateId', 'length', 'max'=>10),
			array('countryId', 'length', 'max'=>3),
			array('googleReference', 'length', 'max'=>255),
			array('googlePlaceId', 'length', 'max'=>50),
			array('updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cityName, shortName, postalCode, stateId, countryId, regionId, googleReference, googlePlaceId, isDisabled, disableReason, createDate, updateDate, createId, updateId', 'safe', 'on'=>'search'),
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
			'region' => array(self::BELONGS_TO, 'Region', 'regionId'),
			'country' => array(self::BELONGS_TO, 'Country', 'countryId'),
			'state' => array(self::BELONGS_TO, 'State', 'stateId'),
			'resturantCount' => array(self::STAT, 'Restaurant', 'cityId'),
			'userCount' => array(self::STAT, 'User', 'cityId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cityName' => 'City Name',
			'shortName' => 'Short Name',
			'postalCode' => 'Postal Code',
			'stateId' => 'State',
			'countryId' => 'Country',
			'regionId' => 'Region',
			'googleReference' => 'Google Reference',
			'googlePlaceId' => 'Google Place',
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
	public function search(array $options = [])
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('cityName',$this->cityName,true);
		$criteria->compare('shortName',$this->shortName,true);
		$criteria->compare('postalCode',$this->postalCode,true);
		$criteria->compare('stateId',$this->stateId,true);
		$criteria->compare('countryId',$this->countryId,true);
		$criteria->compare('regionId',$this->regionId);
		$criteria->compare('googleReference',$this->googleReference,true);
		$criteria->compare('googlePlaceId',$this->googlePlaceId,true);
		$criteria->compare('isDisabled',$this->isDisabled);
		$criteria->compare('disableReason',$this->disableReason,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('createId',$this->createId,true);
		$criteria->compare('updateId',$this->updateId,true);

		if(isset($options['regionId']) and $options['regionId'] > 0)
			$criteria->addCondition('regionId = '.$options['regionId']);
		
		$order = 't.createDate desc';
		
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'sort'=>array(
						'defaultOrder'=>$order
				),
				'pagination'=>array(
						'pageSize'=>25
				)
		));
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
