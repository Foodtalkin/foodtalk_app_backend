<?php
class NotificationCommand extends CConsoleCommand
{
	
	public function init()
	{
		parent::init();
	
		Yii::app()->attachEventHandler('onError',array($this,'handleError'));
		Yii::app()->attachEventHandler('onException',array($this,'handleError'));
	}
	
	public function handleError(CEvent $event)
	{
		if ($event instanceof CExceptionEvent)
		{
			// handle exception
			// ...
			
		}
		elseif($event instanceof CErrorEvent)
		{
			// handle error
			// ...
		}
	
		$event->handled = TRUE;
		
// 		return true;
	}
	
	
	public function actionIndex() {		

		while (1){
			
			
			try {
				$this->sendNotification();
				sleep(5);
			} catch (Exception $e) {
				echo 'Caught Notificatin exception: [[ START ]] '."\n".  $e->getMessage(). "\n ON ";
				echo date('His')." [[ END ]] \n";
			}
			
			
		}
	}
	public function actionTest() {
		$this->sendNotification();
	
	}
	
	
	
	protected function sendNotification(){

		
		//fetch all events that have not been notified yet
		$events = Event::getEvents(0);
		
		
		foreach($events as $event)
		{
			
			
			$eventType = $event['eventType'];
			$message = '';
		
			//notify followers
			if($eventType == Event::POST_CREATED)
				$message = $event['raiserName'] . ' added a new post.';
			else if($eventType == Event::POST_LIKED)
				$message = $event['raiserName'] . ' liked a post of ' . $event['relatedUserName'];
			else if($eventType == Event::POST_FLAGGED)
				$message = $event['raiserName'] . ' flagged a post of ' . $event['relatedUserName'];
			else if($eventType == Event::COMMENT_CREATED)
				$message = $event['raiserName'] . ' commented on a post of ' . $event['relatedUserName'];
			else if($eventType == Event::RESTAURANT_MARKED_FAVOURITE)
				$message = $event['raiserName'] . ' marked a restaurant as favourite.';
			else if($eventType == Event::USER_FOLLOWED)
				$message = $event['raiserName'] . ' is now following ' . $event['relatedUserName'];
		
			if(!empty($message))
			{
				//fetch all followers of the event raiser or related user
				$sql = Follower::getQueryForFollower($event['raiserId']);
		
				if($event['relatedUserId'])
					$sql .= ' OR f.followedUserId =' . $event['relatedUserId'];
		
// 				$followers = Yii::app()->db->createCommand($sql)->queryAll(true);
// 				foreach($followers as $follower)
// 				{
// 					if($follower['id'] != $event['raiserId'] && $follower['id'] != $event['relatedUserId'])
// 					{
// // 						$did = Notification::saveNotification($follower['id'], Notification::NOTIFICATION_GROUP_WORLD, $message, $event['id']);
// // 						error_log('DID :   => '.$did);
// 						//send push notification
// 						/**----- commented as no followers will be notified, as suggested by the client
// 						$session = Session::model()->findByAttributes(array('deviceToken'=>$follower['deviceToken']));
// 						if($session)
// 						{
// 						$session->deviceBadge += 1;
// 						$session->save();
// 						sendApnsNotification($follower['deviceToken'], $message, $follower['deviceBadge']);
// 						}
// 						*****/
// 					}
// 				}
			}
		
			//notify the related user
			if($event['relatedUserId'] && $event['relatedUserId'] != $event['raiserId'])
			{
				if($eventType == Event::POST_LIKED)
					$message = $event['raiserName'] . ' liked your post.';
				else if($eventType == Event::POST_FLAGGED)
					$message = $event['raiserName'] . ' flagged your post.';
				else if($eventType == Event::COMMENT_CREATED)
					$message = $event['raiserName'] . ' commented on your post.';
				else if($eventType == Event::USER_FOLLOWED)
					$message = $event['raiserName'] . ' is now following you.';
				else if($eventType == Event::USER_MENTIONED_COMMENT){
					$message = $event['raiserName'] . ' mentioned you in a comment.';
// 					$eventType = Event::COMMENT_CREATED;
				}
				else if($eventType == Event::USER_MENTIONED_POST){
					$message = $event['raiserName'] . ' mentioned you in a post.';
// 					$eventType = Event::COMMENT_CREATED;
				}
				
				
				if(!empty($message))
				{
					
					$notfyId = Notification::saveNotification($event['relatedUserId'], Notification::NOTIFICATION_GROUP_YOU, $message, $event['id']);
					$session = Session::model()->findByAttributes(array('userId'=>$event['relatedUserId']));
// 					error_log('SESSION_LOG : '. print_r( $session, true ) );
								
					if(!is_null($session) && !empty($session) && $session->deviceToken)
					{
						
						$notifyData = array(
// 								"id" => $notfyId,
// 								"receiverId" => $event['relatedUserId'],
// 								"notificationGroup" => Notification::NOTIFICATION_GROUP_YOU,
								"alert" => $message,
// 								"eventId" => $event['id'],
// 								"isSeen" => "0",
								"eventType" => $eventType,
// 								"raiserId" => $event['raiserId'],
// 								"raiserName" => $event['raiserName'],
// 								"relatedUserId" => $event['relatedUserId'],
// 								"relatedUserName" => $event['relatedUserName'],
								"elementId" => $event['elementId'],
								"eventDate" => $event['eventDate'],
// 								"isNotified" => $event['isNotified'],
								'badge' => $session->deviceBadge,
								'sound' => 'default'
						);
						
// 							echo 'Notify Data : ';
// 						print_r($notifyData);
						
						$session->deviceBadge += 1;
						$session->save();
						sendApnsNotification($session->deviceToken, $message, $session->deviceBadge, $notifyData);
					}
				}
			}elseif( isset($event['message']) && $event['message'] !=null){
				
				
				$devices = array();
				$sql = 'SELECT id, deviceToken, deviceBadge from session where role="manager"';
				
			        $rows = Yii::app()->db->createCommand($sql)->queryAll(true);
		        	$chunk=1;		        
		        
				foreach ($rows as $row){
					$devices[] =  array(
							'deviceToken'=>$row['deviceToken'],
							'notifyData' => array('aps'=> array(
									"alert" => $event['message'],
									"eventType" => $event['eventType'],
									"elementId" => $event['id'],
									"eventDate" => $event['eventDate'],
									'badge' => $row['deviceBadge'],
									'sound' => 'default'
								)
							)
					);
					
					
					if($chunk > 4){
						sendApnsNotification($devices, '');
						$chunk=1;
						$devices = array();
					}else{
						$chunk++;
					}

				}
				
								
				if(!empty($devices)){
					echo 'Sending...'.date(DATE_RFC850)."\n";
					sendApnsNotification($devices, '');
					echo 'done '.date(DATE_RFC850)."\n";
				}
// 				$notfyId = Notification::saveNotification($event['relatedUserId'], Notification::NOTIFICATION_GROUP_YOU, $message, $event['id']);

// 				$insertSql = 'INSERT into notification (receiverId, notificationGroup, message, eventId)  (select id, "1", "'.$event['message'].'", '.$event['id'].' from user where isDisabled = 0 and role="manager")';
// 				Yii::app()->db->createCommand($insertSql)->query();
				
			}
			Event::model()->updateByPk($event['id'], array('isNotified'=>1));
		}
		
		
		
	}
}
