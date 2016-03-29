<?php
class FavouriteController extends ServiceBaseController
{
    /**
     * Add favourite to a restaurant
     */
    public function actionAdd()
    {
        $apiName = 'favourite/add';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantId']) || empty($_JSON['restaurantId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $restaurantId = filter_var($_JSON['restaurantId'], FILTER_SANITIZE_NUMBER_INT);
                    $restaurant = Restaurant::model()->findByPk($restaurantId);
                    if (is_null($restaurant))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected restaurant does not exist.');
                    else
                    {
                        $oldFavourite = Favourite::model()->findByAttributes(array('userId'=>$userId, 'restaurantId'=>$restaurantId));
                        if(!is_null($oldFavourite))
                            $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have already marked this restaurant as favourite.');
                        else
                        {
                            $favourite = new Favourite('add_api');
                            $favourite->userId = $userId;
                            $favourite->restaurantId = $restaurantId;
                            
                            //save record
                            $favourite->save();
                            if ($favourite->hasErrors()) 
                            {
                                throw new Exception(print_r($favourite->getErrors(), true), WS_ERR_UNKNOWN);
                            }
                            
                            //save event
                            Event::saveEvent(Event::RESTAURANT_MARKED_FAVOURITE, $userId, $favourite->id, $favourite->createDate);

                            //send notifications to followers of the user
//                            $sql = Follower::getQueryForFollower($userId);
//                            $sql .= " AND (s.deviceToken is not null OR s.deviceToken!='')";
//                            $followers = Yii::app()->db->createCommand($sql)->queryAll(true);
//                            $message = $user->userName . ' has marked a restaurant as favourite.';
//
//                            foreach($followers as $follower)
//                            {
//                                $session = Session::model()->findByAttributes(array('deviceToken'=>$follower['deviceToken']));
//                                if($session)
//                                {
//                                    $session->deviceBadge += 1;
//                                    $session->save();
//                                    sendApnsNotification($follower['deviceToken'], $message, $follower['deviceBadge']);
//                                }
//                            }

                            $result = array(
                                'api' => $apiName,
                                'apiMessage' => 'Your favourite marked successfully.',
                                'status' => 'OK',
                                'favouriteId' => $favourite->id
                            );
                        }
                    }
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Remove favourite from a restaurant
     */
    public function actionDelete()
    {
        $apiName = 'favourite/delete';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantId']) || empty($_JSON['restaurantId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                $restaurantId = filter_var($_JSON['restaurantId'], FILTER_SANITIZE_NUMBER_INT);
                $restaurant = Restaurant::model()->findByPk($restaurantId);
                    
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else if (is_null($restaurant))
                    $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected restaurant does not exist.');
                else
                {
                    $favourite = Favourite::model()->findByAttributes(array('userId'=>$userId, 'restaurantId'=>$restaurantId));
                    if(is_null($favourite))
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have not marked this restaurant as favourite yet.');
                    else
                    {
                        $favourite->delete();
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Your favourite removed successfully.',
                            'status' => 'OK'
                        );
                    }
                }
            }
        } 
        catch (Exception $e)
        {
            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
        }
        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * List favourites of a restaurant
     */
//    public function actionListByRestaurant()
//    {
//        $apiName = 'favourite/listByRestaurant';
//        $sessionId = null;
//        
//        $_JSON = $this->getJsonInput();
//        
//        try
//        {
//            if(!isset($_JSON) || empty($_JSON))
//                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
//            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
//                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
//            else if(!isset($_JSON['restaurantId']) || empty($_JSON['restaurantId']))
//                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant id whose favourites are to be listed.');
//            else
//            {
//                $userId = $this->isAuthentic($_JSON['sessionId']);
//                $user = User::model()->findByPk($userId);
//                if (is_null($user))
//                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
//                else
//                {
//                    $restaurantId = filter_var($_JSON['restaurantId'], FILTER_SANITIZE_NUMBER_INT);
//                    $restaurant = Restaurant::model()->findByPk($restaurantId);
//                    if (is_null($restaurant))
//                        $result = $this->error($apiName, WS_ERR_WONG_USER, 'Selected restaurant does not exist.');
//                    else
//                    {
//                        $imagePath = Yii::app()->getBaseUrl(true) . '/images/user/';
//                        $thumbPath = Yii::app()->getBaseUrl(true) . '/images/user/thumb/';
//                        
//                        $sql = Favourite::getQuery($imagePath, $thumbPath, $restaurantId, $userId);
//                        $favourites = Yii::app()->db->createCommand($sql)->queryAll(true);
//
//                        $result = array(
//                            'api' => $apiName,
//                            'apiMessage' => 'Favourites fetched successfully.',
//                            'status' => 'OK',
//                            'favourites' => $favourites
//                        );
//                    }
//                }
//            }
//        } 
//        catch (Exception $e)
//        {
//            $result = $this->error($apiName, $e->getCode(), Yii::t('app', $e->getMessage()));
//        }
//        $this->sendResponse(json_encode($result, JSON_UNESCAPED_UNICODE));
//    }
}