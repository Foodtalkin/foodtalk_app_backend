<?php
class RestaurantReportController extends ServiceBaseController
{
    /**
     * Add restaurantReport record
     */
    public function actionAdd()
    {
        $apiName = 'restaurantReport/add';
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
            else if(!isset($_JSON['reportType']) || empty($_JSON['reportType']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter report type.');
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
                        $reportType = filter_var($_JSON['reportType'], FILTER_SANITIZE_NUMBER_INT);
                        $oldRestaurantReport = RestaurantReport::model()->findByAttributes(array('userId'=>$userId, 'restaurantId'=>$restaurantId, 'reportType'=>$reportType));
                        if(!is_null($oldRestaurantReport))
                            $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You have already reported this issue.');
                        else
                        {
                            $restaurantReport = new RestaurantReport('api_insert');
                            $restaurantReport->userId = $userId;
                            $restaurantReport->restaurantId = $restaurantId;
                            $restaurantReport->reportType = $reportType;
                            
                            //save record
                            $restaurantReport->save();
                            if ($restaurantReport->hasErrors()) 
                            {
                                throw new Exception(print_r($restaurantReport->getErrors(), true), WS_ERR_UNKNOWN);
                            }
                            
                            $result = array(
                                'api' => $apiName,
                                'apiMessage' => 'Your report saved successfully.',
                                'status' => 'OK',
                                'restaurantReportId' => $restaurantReport->id
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
     * Remove restaurantReport from a restaurant
     */
    public function actionDelete()
    {
        $apiName = 'restaurantReport/delete';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['restaurantReportId']) || empty($_JSON['restaurantReportId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter restaurant report id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                
                $restaurantReportId = filter_var($_JSON['restaurantReportId'], FILTER_SANITIZE_NUMBER_INT);
                $restaurantReport = RestaurantReport::model()->findByPk($restaurantReportId);
                    
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else if (is_null($restaurantReport))
                    $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'Selected restaurant report does not exist.');
                else
                {
                    if($restaurantReport->userId != $user->id)
                        $result = $this->error($apiName, WS_ERR_WONG_VALUE, 'You are not allowed to delete this report.');
                    else
                    {
                        $restaurantReport->delete();
                        $result = array(
                            'api' => $apiName,
                            'apiMessage' => 'Your restaurant report removed successfully.',
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
}