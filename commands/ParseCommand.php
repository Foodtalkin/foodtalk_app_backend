<?php
class ParseCommand extends CConsoleCommand
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

	public function actionClass() {
	
		while (1){
			try {
				$this->sendPushNotification();
				sleep(5);
			} catch (Exception $e) {
				echo 'Caught Notificatin exception: [[ START ]] '."\n".  $e->getMessage(). "\n ON ";
				echo date('His')." [[ END ]] \n";
			}
		}
	}
	
	
	public function actionTest(){
		
		echo "SENDING PUSH ...\n";
		$this->sendPushNotification(false);
		
// 		$notificationType = Notification::NOTIFICATION_PUBLIC;
// 		$notifyData = array(
// 				"data" => array(
// 						"alert" => 'Hey FoodTalkers checkout whats new to Eat!',
// 						"eventType" => Event::HOME_FEED
// 				)
// 		);
		
// 		PushParseNotification($notificationType, $notifyData);
		echo "DONE PUSH ...\n";		
	}
	

	protected function sendNotification(){
	
		//fetch all events that have not been notified yet
		$events = Event::getEvents(0);
	
		foreach($events as $event)
		{
			$eventType = $event['eventType'];
			$message = '';
			$notificationType = false;
				
			switch ($eventType) {
				case Event::POST_LIKED:
					$message = $event['raiserName'] . ' liked your post.';
					$notificationType = Notification::NOTIFICATION_GROUP_YOU;
					break;
				case Event::POST_FLAGGED:
					$message = $event['raiserName'] . ' flagged your post.';
					$notificationType = Notification::NOTIFICATION_GROUP_YOU;
					break;
				case Event::COMMENT_CREATED:
					$message = $event['raiserName'] . ' commented on your post.';
					$notificationType = Notification::NOTIFICATION_GROUP_YOU;
					break;
				case Event::USER_FOLLOWED:
					$message = $event['raiserName'] . ' is now following you.';
					$notificationType = Notification::NOTIFICATION_GROUP_YOU;
					break;
				case Event::USER_MENTIONED_COMMENT:
					$message = $event['raiserName'] . ' mentioned you in a comment.';
					$notificationType = Notification::NOTIFICATION_GROUP_YOU;
					break;
				case Event::USER_MENTIONED_POST:
					$message = $event['raiserName'] . ' mentioned you in a post.';
					$notificationType = Notification::NOTIFICATION_GROUP_YOU;
					break;
					 
					// PUBLIC NOTIFICATION NOTIFICATION_PUBLIC
				case Event::HOME_FEED:
					$message = $event['message'];
					$notificationType = Notification::NOTIFICATION_PUBLIC;
					break;
				case Event::DISCOVER_PAGE:
					$message = $event['message'];
					$notificationType = Notification::NOTIFICATION_PUBLIC;
					break;
				case Event::USER_PROFILE:
					$message = $event['message'];
					$notificationType = Notification::NOTIFICATION_PUBLIC;
					break;
					 
				case Event::RESTAURANT_PROFILE :
					$message = $event['message'];
					$notificationType = Notification::NOTIFICATION_PUBLIC;
					break;
			}
				
			if($notificationType){
	
				if($notificationType == Notification::NOTIFICATION_GROUP_YOU){
						
					$notfyId = Notification::saveNotification($event['relatedUserId'], Notification::NOTIFICATION_GROUP_YOU, $message, $event['id']);
						
					$notifyData = array(
							"receiverId" => $event['relatedUserId'],
							"data" => array(
									"alert" => $message,
									"eventType" => $eventType,
									"elementId" => $event['elementId']
									// 									"eventDate" => $event['eventDate']
	
							),
					);
				}
					
				if($notificationType == Notification::NOTIFICATION_PUBLIC){
					$notifyData = array(
							"data" => array(
									"alert" => $message,
									"eventType" => $eventType,
									"elementId" => $event['elementId']
									// 									"eventDate" => $event['eventDate']
							),
					);
				}
	
				PushParseNotification($notificationType, $notifyData);
	
			}
				
			Event::model()->updateByPk($event['id'], array('isNotified'=>1));
		}
	}
	
	
	protected function sendPushNotification($push = true){
		
		//fetch all events that have not been notified yet
		$events = Event::getEvents(0);
		
		foreach($events as $event)
		{
			$eventType = $event['eventType'];
			$eventGroup = $event['eventGroup'];
			$message = '';
// 			$notificationType = false;
			
			switch ($eventGroup) {
		    case Notification::NOTIFICATION_GROUP_YOU:
		    	
		    	if(strlen(trim($event['message']))>0)
			    	$message = $event['message'];
		    	else
			    	$message = $event['raiserName'] . $event['defaultMessage'];
		    	
		    	$notificationType = Notification::NOTIFICATION_GROUP_YOU;
		    	break;
	    		
			// PUBLIC NOTIFICATION NOTIFICATION_PUBLIC	
    		case Notification::NOTIFICATION_PUBLIC:
    			$message = $event['message'];
    			$notificationType = Notification::NOTIFICATION_PUBLIC;
    			break;

			}
			if($notificationType){
				
				
				if($notificationType == Notification::NOTIFICATION_GROUP_YOU){
					
					$notfyId = Notification::saveNotification($event['relatedUserId'], Notification::NOTIFICATION_GROUP_YOU, $message, $event['id']);
					
					
					$data['alert'] = $message;
					$data['eventType'] = $eventType;
// 					if(isset($data['elementId']) && $data['elementId'] && $data['elementId']!='')
						$data['elementId'] = $event['elementId'];
					$data['class'] = $event['className'];
						
					
					$notifyData = array(
							"receiverId" => $event['relatedUserId'],
							"data" => $data
					);
				}
				if($notificationType == Notification::NOTIFICATION_PUBLIC){
					
					$data['alert'] = $message;
					$data['eventType'] = $eventType;
// 					if(isset($data['elementId']) && $data['elementId'] && $data['elementId']!='')
						$data['elementId'] = $event['elementId'];
					$data['class'] = $event['className'];
					
					$notifyData = array(
							"data" => $data
					);
				}
				if($push)
				PushParseNotification($notificationType, $notifyData, [$event['channel']]);
				
			}
			
			Event::model()->updateByPk($event['id'], array('isNotified'=>1));
		}
	}


}
