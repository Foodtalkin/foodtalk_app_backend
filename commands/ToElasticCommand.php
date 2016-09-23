<?php
class ToElasticCommand extends CConsoleCommand
{
	
	public function actionIndex() {		
	
// 		$objects = ToElastic::model()->findAllByAttributes(array('process'=>1));
		
		$objects = ToElastic::model()->findAll(array('condition' => 'process = 1','limit' => 50));
		
		$dishes = array();
		$restaurants = array();
		$users = array();
		
		$processIds = array();
		
		foreach ($objects as $obj){
			
			$processIds[] = $obj->id;
			switch ($obj->entity)
			{
				case "dish":
					$dishes['dishIds'][] = $obj->entityId; 
					$dishes['processIds'][] = $obj->id;
					break;
					
				case "restaurant":
					$restaurants['restaurantIds'][] = $obj->entityId;
					$restaurants['processIds'][] = $obj->id;
					break;
					
				case "user":
					$users['userIds'][] = $obj->entityId;
					$users['processIds'][] = $obj->id;
					break;
			}
		}
		
		
		$dataToElastic = array();
		
		if(!empty($dishes)){
			$dataToElastic[] = self::processDish($dishes['dishIds']);
			
		}
		if(!empty($restaurants)){
			$dataToElastic[] = self::processResturant($dishes['restaurantIds']);
		}
		if(!empty($users)){
			$dataToElastic[] = self::processUser($dishes['userIds']);
		}
		
		if(!empty($dataToElastic)){
			$responce = es(implode("\n", $elasticData), '/foodtalkindex/_bulk', 'POST');
			
			print_r($responce);
			
			$sql = 'update toElastic set process = 0 where id in ('.implode(',', $processIds).')';
			Yii::app()->db->createCommand($sql)->query();
				
		}
		
		
	}
	
	private function processUser($userIds) {
		
		$sql = 'Select id, isDisabled, userName, fullName, region, latitude, longitude, cityId from user where id in ('.implode(',', $userIds).')';
		$dishes = Yii::app()->db->createCommand($sql)->queryAll(true);
		
		$elasticData = array();
		
		foreach ($dishes as $dish){
			$data = new ElasticData($dish,'user');
			$elasticData[] = $data->toString();
		}
		return implode("\n", $elasticData);
		
	}
	
	private  function processResturant($restaurantIds) {
		
		$sql = 'Select id, isDisabled, restaurantName, address, region, latitude, longitude, cityId from restaurant where id in ('.implode(',', $restaurantIds).')';
		$dishes = Yii::app()->db->createCommand($sql)->queryAll(true);
		
		$elasticData = array();
		
		foreach ($dishes as $dish){
			$data = new ElasticData($dish,'restaurant');
			$elasticData[] = $data->toString();
		}
		return implode("\n", $elasticData);
	}
	
	private function processDish($disheIds) {
		
		$sql = 'Select id, dishName, isDisabled from dish where id in ('.implode(',', $disheIds).')';
		$dishes = Yii::app()->db->createCommand($sql)->queryAll(true);
		
		$elasticData = array();		
		
		foreach ($dishes as $dish){
			$data = new ElasticData($dish,'dish');
			$elasticData[] = $data->toString();
		}
		return implode("\n", $elasticData);
	}


	public function actionImport($table, $counter=100){
		
		
		
		
		$sql = "SELECT MAX(id) AS maxId FROM $table where 1";
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		
		$start = 0;
		$end = $start + $counter;
		
		while ($result['maxId'] >= $end){
			
			$ids = array();
			for ($i=$start; $i<$end; $i++ ){
				$ids[]=$i;
			}
			$start = $end;
			$end = $start + $counter;
			$responce = es(self::processDish($ids), '/foodtalkindex/_bulk', 'POST');
			
		}
		

		
		
		
		
// 		var_dump($result);
		
	}
	
	
}

class ElasticData {
	
	private $action;
	private $body = NULL;
	
	
	function __construct($data, $type){
		
		if($data['isDisabled']){
			$this->action = '{"delete":{"_id": "'.$data['id'].'","_type": "'.$type.'2"}}';
		}
		else{
			$this->action = '{"index":{"_id": "'.$data['id'].'","_type": "'.$type.'2"}}';
			unset($data['id']);
			unset($data['isDisabled']);
			$this->body = json_encode( $data);
		}
		
	}
	
	public function toString(){
		if ($this->body)
			return $this->action."\n".$this->body;
		else
			return $this->action;
	}
	
	// 		{"index":{"_id": "30003","_type": "dish"}}
	// 		{"dishName": "sushi chacha"}
	
	
}


