<?php

class CronController extends Controller
{
    /**
     * Run the cron job
     */
    public function actionRun()
    {
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

                $followers = Yii::app()->db->createCommand($sql)->queryAll(true);
                foreach($followers as $follower)
                {
                    if($follower['id'] != $event['raiserId'] && $follower['id'] != $event['relatedUserId'])
                    {
                        Notification::saveNotification($follower['id'], Notification::NOTIFICATION_GROUP_WORLD, $message, $event['id']);
                        
                        //send push notification
                        /**----- commented as no followers will be notified, as suggested by the client
                        $session = Session::model()->findByAttributes(array('deviceToken'=>$follower['deviceToken']));
                        if($session)
                        {
                            $session->deviceBadge += 1;
                            $session->save();
                            sendApnsNotification($follower['deviceToken'], $message, $follower['deviceBadge']);
                        }
                        *****/
                    }
                }
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
                
                if(!empty($message))
                {
                    Notification::saveNotification($event['relatedUserId'], Notification::NOTIFICATION_GROUP_YOU, $message, $event['id']);
                    $session = Session::model()->findByAttributes(array('userId'=>$event['relatedUserId']));
                    if(!is_null($session) && !empty($session) && $session->deviceToken)
                    {
                        $session->deviceBadge += 1;
                        $session->save();
                        sendApnsNotification($session->deviceToken, $message, $session->deviceBadge);
                    }
                }
            }
            Event::model()->updateByPk($event['id'], array('isNotified'=>1));
        }
    }
}
