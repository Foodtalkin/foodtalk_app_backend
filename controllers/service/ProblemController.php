<?php
class ProblemController extends ServiceBaseController
{
    /**
     * Create new problem
     */
    public function actionAdd()
    {
        $apiName = 'problem/add';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else if(!isset($_JSON['problemArea']) || empty($_JSON['problemArea']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter problem area.');
            else if(!isset($_JSON['details']) || empty($_JSON['details']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter problem details.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $problem = new Problem('create_api');
                    $problem->userId = $userId;
                    $problem->problemArea = filter_var($_JSON['problemArea'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    $problem->details = filter_var($_JSON['details'], FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES);
                    
                    //save record
                    $problem->save();
                    if ($problem->hasErrors()) 
                    {
                        throw new Exception(print_r($problem->getErrors(), true), WS_ERR_UNKNOWN);
                    }
                    
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Your problem is saved successfully.',
                        'status' => 'OK',
                        'problemId' => $problem->id
                    );
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
     * List problems
     */
    public function actionList()
    {
        $apiName = 'problem/list';
        $sessionId = null;
        
        $_JSON = $this->getJsonInput();
        
        try
        {
            if(!isset($_JSON) || empty($_JSON))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'No input received.');
            else if(!isset($_JSON['sessionId']) || empty($_JSON['sessionId']))
                $result = $this->error($apiName, WS_ERR_POST_PARAM_MISSED, 'Please enter session id.');
            else
            {
                $userId = $this->isAuthentic($_JSON['sessionId']);
                $user = User::model()->findByPk($userId);
                if (is_null($user))
                    $result = $this->error($apiName, WS_ERR_WONG_USER, 'Please login before using this service.');
                else
                {
                    $problems = Problem::getByUserId($userId);
                    $result = array(
                        'api' => $apiName,
                        'apiMessage' => 'Problem records fetched successfully.',
                        'status' => 'OK',
                        'problems' => $problems
                    );
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